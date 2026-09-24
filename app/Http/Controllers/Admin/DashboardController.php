<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $adminId = Auth::id();
        $totalProducts = Product::where('seller_id', $adminId)->count();
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
            ->where('seller_id', $adminId)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->take(6)
            ->get();
        $monthlySales = collect(range(5, 0))->map(function (int $monthsAgo) {
            $month = now()->subMonths($monthsAgo);

            return [
                'label' => $month->translatedFormat('M Y'),
                'total' => (float) Order::whereHas('payment', fn ($query) => $query->where('payment_status', 'Verified'))
                    ->whereBetween('order_date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                    ->sum('total_amount'),
            ];
        });
        $topProducts = OrderDetail::with('product')
            ->whereHas('order.payment', fn ($query) => $query->where('payment_status', 'Verified'))
            ->select('product_id')
            ->selectRaw('SUM(quantity) as total_quantity, SUM(subtotal) as total_sales')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->take(5)
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
            'lowStockProducts',
            'monthlySales',
            'topProducts'
        ));
    }
}
