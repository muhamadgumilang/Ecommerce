<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalProducts = Product::count();
        $totalUsers = User::count();
        $totalSellers = User::where('role', 'Seller')->count();
        $totalCustomers = User::where('role', 'Customer')->count();
        $totalOrders = Order::count();
        $pendingPayments = Payment::where('payment_status', 'Pending')->count();
        $verifiedRevenue = Order::whereHas('payment', fn ($query) => $query->where('payment_status', 'Verified'))
            ->sum('total_amount');
        $statusCounts = Order::select('order_status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('order_status')
            ->pluck('total', 'order_status');
        $recentOrders = Order::with(['customer', 'payment'])
            ->latest('order_date')
            ->take(6)
            ->get();
        $lowStockProducts = Product::with('seller')
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(6)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalUsers',
            'totalSellers',
            'totalCustomers',
            'totalOrders',
            'pendingPayments',
            'verifiedRevenue',
            'statusCounts',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
