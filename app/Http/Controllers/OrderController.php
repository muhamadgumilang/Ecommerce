<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Checkout;
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderController extends Controller
{
    public function showCheckout()
    {
        $user = Auth::user();
        $cart = Cart::where('customer_id', $user->user_id)->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $selectedCartItemId = session('checkout_cart_item_id');
        if ($selectedCartItemId) {
            $cart->setRelation('cartItems', $cart->cartItems->where('cart_item_id', (int) $selectedCartItemId)->values());
        }

        if ($cart->cartItems->isEmpty()) {
            session()->forget('checkout_cart_item_id');
            return redirect()->route('cart.index')->with('error', 'Produk yang dipilih tidak tersedia.');
        }

        $subtotal = $cart->cartItems->sum(function ($item) {
            return (int) round((float) $item->product->price) * (int) $item->quantity;
        });

        // Ambil data ongkir dalam format lookup yang stabil untuk JavaScript.
        $shippingCosts = ShippingCost::where('origin', '1') // Surabaya sebagai origin default
            ->get()
            ->mapWithKeys(function ($shipping) {
                return [
                    $shipping->courier . '|' . $shipping->destination . '|' . $shipping->service => [
                        'courier' => $shipping->courier,
                        'destination' => $shipping->destination,
                        'service' => $shipping->service,
                        'cost' => (int) $shipping->cost,
                        'description' => $shipping->description,
                    ],
                ];
            })
            ->all();

        // Daftar kota tujuan unik
        $destinations = ShippingCost::where('origin', '1')
            ->select('destination')
            ->distinct()
            ->get();

        return view('checkout.index', compact('cart', 'subtotal', 'shippingCosts', 'destinations'));
    }

    public function show(Order $order)
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak melihat order ini.');

        $order->load(['orderDetails.product', 'checkout', 'payment']);

        return view('orders.show', compact('order'));
    }

    public function paymentStatus(Order $order): JsonResponse
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak melihat status pembayaran order ini.');

        $order->load('payment');

        return response()->json([
            'payment_status' => $order->payment?->payment_status ?? 'Pending',
            'order_status' => $order->order_status,
        ]);
    }

    public function processCheckout(Request $request)
    {
        $fields = $request->validate([
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
            'courier' => 'nullable|string',
            'service' => 'nullable|string',
            'destination' => 'nullable|string',
        ]);

        // Hitung ongkir berdasarkan pilihan
        $shippingMethod = $request->input('courier', 'Pengiriman Standar');
        $shippingFee = 0;
        
        if ($request->courier && $request->service && $request->destination) {
            $shipping = ShippingCost::where('courier', $request->courier)
                ->where('service', $request->service)
                ->where('destination', $request->destination)
                ->first();
            if ($shipping) {
                $shippingFee = $shipping->cost;
                $shippingMethod = $shipping->courier_label . ' - ' . $shipping->description;
            }
        }

        $user = Auth::user();
        $cart = Cart::where('customer_id', $user->user_id)->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $selectedCartItemId = session('checkout_cart_item_id');
        if ($selectedCartItemId) {
            $cart->setRelation('cartItems', $cart->cartItems->where('cart_item_id', (int) $selectedCartItemId)->values());
        }

        if ($cart->cartItems->isEmpty()) {
            session()->forget('checkout_cart_item_id');
            return redirect()->route('cart.index')->with('error', 'Produk yang dipilih tidak tersedia.');
        }

        $cartItems = $cart->cartItems;

        $order = DB::transaction(function () use ($cartItems, $fields, $shippingMethod, $shippingFee, $user) {
            $lineItems = [];

            foreach ($cartItems as $item) {
                $product = $item->product()->lockForUpdate()->first();

                if (!$product || $item->quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'stock' => "Stok produk " . ($product?->product_name ?? 'yang dipilih') . ' tidak mencukupi.',
                    ]);
                }

                $unitPrice = (int) round((float) $product->price);
                $lineItems[] = [
                    'item' => $item,
                    'product' => $product,
                    'subtotal' => $unitPrice * (int) $item->quantity,
                ];
                $product->decrement('stock', $item->quantity);
            }

            $subtotal = collect($lineItems)->sum('subtotal');
            $order = Order::create([
                'customer_id' => $user->user_id,
                'total_amount' => $subtotal + $shippingFee,
                'order_status' => 'Pending Payment',
            ]);

            Checkout::create([
                'order_id' => $order->order_id,
                'shipping_address' => $fields['shipping_address'],
                'courier' => $shippingMethod,
                'shipping_fee' => $shippingFee,
                'notes' => $fields['notes'] ?? null,
            ]);

            foreach ($lineItems as $lineItem) {
                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $lineItem['product']->product_id,
                    'quantity' => $lineItem['item']->quantity,
                    'subtotal' => $lineItem['subtotal'],
                ]);
            }

            CartItem::whereIn('cart_item_id', $cartItems->pluck('cart_item_id'))->delete();

            return $order;
        });

        session()->forget('checkout_cart_item_id');

        return redirect()->route('orders.index')->with('success', 'Checkout berhasil! Silakan lakukan pembayaran.');
    }

    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('customer_id', $user->user_id)
            ->with(['orderDetails.product', 'payment', 'checkout'])
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    public function cancel(Order $order)
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak membatalkan order ini.');
        abort_unless($order->order_status === 'Pending Payment', 422, 'Pesanan yang sudah diproses tidak dapat dibatalkan.');

        $order->load(['orderDetails.product', 'payment']);

        DB::transaction(function () use ($order): void {
            if (! $order->payment?->stock_released) {
                foreach ($order->orderDetails as $detail) {
                    $detail->product()->lockForUpdate()->first()?->increment('stock', $detail->quantity);
                }
            }

            $order->update(['order_status' => 'Cancelled']);
        });

        return redirect()->route('orders.show', $order)->with('success', 'Pesanan berhasil dibatalkan.');
    }

}
