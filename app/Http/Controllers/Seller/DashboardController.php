<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $sellerId = $request->user()->user_id;

        // Total produk aktif milik seller ini
        $totalProducts = Product::where('seller_id', $sellerId)->count();

        // Total pesanan masuk yang mengandung produk seller ini
        $incomingOrdersCount = Order::whereHas('orderDetails.product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->count();

        // Total pendapatan dari produk seller yang sudah dibayar / diproses / selesai
        $totalRevenue = OrderDetail::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->whereHas('order', function ($q) {
            $q->whereIn('order_status', ['Processing', 'Shipped', 'Completed']);
        })->sum('subtotal');

        // 5 Pesanan terbaru
        $recentOrders = Order::whereHas('orderDetails.product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->with([
            'customer',
            'orderDetails' => function ($q) use ($sellerId) {
                $q->whereHas('product', function ($p) use ($sellerId) {
                    $p->where('seller_id', $sellerId);
                })->with('product');
            }
        ])->latest('order_date')->take(5)->get();

        return view('seller.dashboard', compact(
            'totalProducts',
            'incomingOrdersCount',
            'totalRevenue',
            'recentOrders'
        ));
    }
}
