<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

        return view('checkout.index', compact('cart', 'subtotal'));
    }

    public function show(Order $order)
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak melihat order ini.');

        $order->load(['orderDetails.product', 'checkout', 'payment']);

        return view('orders.show', compact('order'));
    }

    public function processCheckout(Request $request)
    {
        $fields = $request->validate([
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $shippingMethod = 'Pengiriman standar';
        $shippingFee = 0;

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
}
