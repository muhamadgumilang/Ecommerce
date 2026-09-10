<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            return $item->product->price * $item->quantity;
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

        // 1. Hitung Total Pesanan
        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $totalAmount = $subtotal + $shippingFee;

        // 2. Simpan Data Order
        $order = Order::create([
            'customer_id' => $user->user_id,
            'total_amount' => $totalAmount,
            'order_status' => 'Pending Payment',
        ]);

        // 3. Simpan Detail Checkout
        Checkout::create([
            'order_id' => $order->order_id,
            'shipping_address' => $fields['shipping_address'],
            'courier' => $shippingMethod,
            'shipping_fee' => $shippingFee,
            'notes' => $fields['notes'] ?? null,
        ]);

        // 4. Pindahkan Barang ke Order Details
        foreach ($cart->cartItems as $item) {
            OrderDetail::create([
                'order_id' => $order->order_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'subtotal' => $item->product->price * $item->quantity,
            ]);
        }

        // 5. Bersihkan Keranjang
        CartItem::whereIn('cart_item_id', $cart->cartItems->pluck('cart_item_id'))->delete();
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
