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

        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('checkout.index', compact('cart', 'subtotal'));
    }

    public function show(Order $order)
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak melihat order ini.');

        return view('orders.show', compact('order'));
    }

    public function processCheckout(Request $request)
    {
        $fields = $request->validate([
            'shipping_address' => 'required|string',
            'courier' => 'required|string|max:50',
            'shipping_fee' => 'required|numeric',
            'notes' => 'nullable|string',
        ]);

        $user = Auth::user();
        $cart = Cart::where('customer_id', $user->user_id)->with('cartItems.product')->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // 1. Hitung Total Pesanan
        $subtotal = $cart->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $totalAmount = $subtotal + $fields['shipping_fee'];

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
            'courier' => $fields['courier'],
            'shipping_fee' => $fields['shipping_fee'],
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
        CartItem::where('cart_id', $cart->cart_id)->delete();

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
