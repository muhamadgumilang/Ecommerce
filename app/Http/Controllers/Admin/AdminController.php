<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalRevenue = Order::where('order_status', 'Completed')->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'Customer')->count();
        $recentOrders = Order::with('customer')->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 'totalOrders', 'totalProducts', 'totalCustomers', 'recentOrders'
        ));
    }

    public function orders()
    {
        $orders = Order::with(['customer', 'checkout', 'payment', 'orderDetails.product'])->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:Pending Payment,Processing,Shipped,Completed,Cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->update(['order_status' => $request->order_status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    public function verifyPayment(Request $request, $payment_id)
    {
        $request->validate([
            'status' => 'required|in:Verified,Failed',
        ]);

        $payment = Payment::findOrFail($payment_id);
        $payment->update([
            'admin_id' => auth()->user()->user_id,
            'payment_status' => $request->status,
        ]);

        if ($request->status === 'Verified') {
            Order::where('order_id', $payment->order_id)->update(['order_status' => 'Processing']);
        }

        return redirect()->back()->with('success', 'Status verifikasi pembayaran berhasil diubah!');
    }

    public function users()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    public function products()
    {
        $products = Product::with('category')->latest()->get();
        return view('admin.products', compact('products'));
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}