<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = $request->user()->user_id;

        // Pesanan yang mengandung produk milik seller ini
        $orders = Order::whereHas('orderDetails.product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->with([
            'customer',
            'payment',
            'orderDetails' => function ($q) use ($sellerId) {
                $q->whereHas('product', function ($p) use ($sellerId) {
                    $p->where('seller_id', $sellerId);
                })->with('product');
            }
        ])->latest('order_date')->paginate(10);

        return view('seller.orders.index', compact('orders'));
    }

    public function show(Request $request, Order $order)
    {
        $sellerId = $request->user()->user_id;

        // Pastikan pesanan ini memang memiliki produk milik seller ini
        $hasSellerProduct = $order->orderDetails()->whereHas('product', function ($p) use ($sellerId) {
            $p->where('seller_id', $sellerId);
        })->exists();

        if (!$hasSellerProduct) {
            abort(403, 'Akses ditolak. Pesanan ini tidak mengandung produk dari toko Anda.');
        }

        $order->load([
            'customer',
            'checkout',
            'payment',
            'orderDetails' => function ($q) use ($sellerId) {
                $q->whereHas('product', function ($p) use ($sellerId) {
                    $p->where('seller_id', $sellerId);
                })->with('product');
            }
        ]);

        return view('seller.orders.show', compact('order'));
    }

    public function ship(Request $request, Order $order)
    {
        $sellerId = $request->user()->user_id;

        $hasSellerProduct = $order->orderDetails()->whereHas('product', function ($p) use ($sellerId) {
            $p->where('seller_id', $sellerId);
        })->exists();

        if (!$hasSellerProduct) {
            abort(403, 'Akses ditolak. Pesanan ini tidak mengandung produk dari toko Anda.');
        }

        // Update status menjadi Shipped (Dikirim)
        $order->update([
            'order_status' => 'Shipped',
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi Dikirim (Shipped)!');
    }
}
