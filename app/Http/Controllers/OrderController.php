<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Checkout;
use App\Services\RajaOngkirClient;
use App\Services\ShippingCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

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

        $calculator = new ShippingCalculator();
        $weightPerItemInGram = 1000;
        $totalWeight = $cart->cartItems->sum(function ($item) use ($weightPerItemInGram) {
            $weight = $item->product->weight_gram ?? $weightPerItemInGram;

            return (int) $weight * (int) $item->quantity;
        });

        $destinations = collect([
            ['destination' => '531', 'label' => 'Bandung / Dayeuhkolot'],
            ['destination' => '113', 'label' => 'Jakarta Timur'],
            ['destination' => '153', 'label' => 'Jakarta Barat'],
        ]);

        $shippingCosts = $destinations->flatMap(function ($destination) use ($calculator, $totalWeight) {
            return $calculator->availableOptions($destination['destination'], $totalWeight);
        })->all();

        $originAddress = 'Dayeuhkolot, Cibedug, RT 4 RW 2';

        return view('checkout.index', compact('cart', 'subtotal', 'shippingCosts', 'destinations', 'originAddress', 'totalWeight'));
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

    public function processCheckout(Request $request, RajaOngkirClient $rajaOngkir)
    {
        $fields = $request->validate([
            'shipping_address' => 'required|string',
            'destination_province' => 'required|string|max:100',
            'destination_regency' => 'required|string|max:100',
            'destination_district' => 'required|string|max:100',
            'destination_village' => 'required|string|max:100',
            'postal_code' => 'required|digits:5',
            'notes' => 'nullable|string',
            'courier' => 'required|string|in:jne,tiki,pos,jnt,sicepat',
            'service' => 'required|string|max:50',
            'destination' => 'required|string',
        ]);

        // Hitung ongkir berdasarkan pilihan
        $shippingMethod = $request->input('courier', 'Pengiriman Standar');
        $shippingFee = 0;

        $user = Auth::user();
        $cart = Cart::where('customer_id', $user->user_id)->with('cartItems.product')->first();

        $selectedCartItemId = session('checkout_cart_item_id');
        if ($cart && $selectedCartItemId) {
            $cart->setRelation('cartItems', $cart->cartItems->where('cart_item_id', (int) $selectedCartItemId)->values());
        }

        $totalWeight = 0;

        if ($cart) {
            $totalWeight = $cart->cartItems->sum(function ($item) {
                $weight = $item->product->weight_gram ?? 1000;

                return (int) $weight * (int) $item->quantity;
            });
        }

        if ($request->courier && $request->service && $request->destination) {
            $destinationId = $fields['destination_regency'];
            $originId = config('services.rajaongkir.origin');
            $costOptions = [];

            if ($originId) {
                try {
                    $costOptions = $rajaOngkir->cost(
                        (string) $originId,
                        $destinationId,
                        $totalWeight,
                        $request->courier,
                    );
                } catch (Throwable $exception) {
                    report($exception);
                }
            }

            $selectedOption = collect($costOptions)->first(fn ($option) =>
                strcasecmp((string) data_get($option, 'service', ''), $request->service) === 0
            );

            if ($selectedOption) {
                $shippingFee = (int) data_get($selectedOption, 'cost.0.value', 0);
            } elseif (!empty($costOptions)) {
                throw ValidationException::withMessages([
                    'service' => 'Layanan pengiriman yang dipilih tidak tersedia untuk tujuan ini.',
                ]);
            } else {
                $fallbackOption = collect((new ShippingCalculator())->availableOptions($destinationId, $totalWeight))
                    ->first(fn ($option) =>
                        strcasecmp($option['courier'], $request->courier) === 0
                        && strcasecmp($option['service'], $request->service) === 0
                    );

                if (!$fallbackOption) {
                    throw ValidationException::withMessages([
                        'service' => 'Layanan pengiriman yang dipilih tidak valid.',
                    ]);
                }

                $shippingFee = (int) $fallbackOption['cost'];
            }

            $shippingMethod = strtoupper($request->courier) . ' - ' . strtoupper($request->service);
        }

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
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
                'destination_province' => $fields['destination_province'],
                'destination_regency' => $fields['destination_regency'],
                'destination_district' => $fields['destination_district'],
                'destination_village' => $fields['destination_village'],
                'postal_code' => $fields['postal_code'],
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
