<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;
<<<<<<< Updated upstream
use App\Models\Category;
=======
>>>>>>> Stashed changes
use Illuminate\Http\Request;

class AdminController extends Controller
{
<<<<<<< Updated upstream
=======
    /**
     * 1. Dashboard Ringkasan Statistik Sistem
     */
>>>>>>> Stashed changes
    public function dashboard()
    {
        $totalRevenue = Order::where('order_status', 'Completed')->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'Customer')->count();
<<<<<<< Updated upstream
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
=======

        // 5 Pesanan terbaru
        $recentOrders = Order::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'total_products' => $totalProducts,
                'total_customers' => $totalCustomers,
                'recent_orders' => $recentOrders
            ]
        ], 200);
    }

    /**
     * 2. Menampilkan Semua Pesanan (Order Management)
     */
    public function getAllOrders(Request $request)
    {
        $query = Order::with(['customer', 'checkout', 'payment', 'orderDetails.product']);

        // Filter berdasarkan status jika dikirim dari frontend Vue
        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }

    /**
     * 3. Update Status Pesanan oleh Admin
     */
    public function updateOrderStatus(Request $request, $order_id)
    {
        $fields = $request->validate([
            'order_status' => 'required|in:Pending Payment,Processing,Shipped,Completed,Cancelled'
        ]);

        $order = Order::find($order_id);

        if (!$order) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        $order->update([
            'order_status' => $fields['order_status']
        ]);

        return response()->json([
            'message' => 'Status pesanan berhasil diperbarui',
            'data' => $order
        ], 200);
    }

    /**
     * 4. Menampilkan Semua Pengguna (User Management)
     */
    public function getAllUsers()
    {
        $users = User::select('user_id', 'name', 'email', 'phone', 'role', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $users
        ], 200);
    }

    /**
     * 5. Hapus Produk oleh Admin
     */
    public function destroyProduct($product_id)
    {
        $product = Product::find($product_id);

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();

        return response()->json([
            'message' => 'Produk berhasil dihapus oleh Admin'
        ], 200);
>>>>>>> Stashed changes
    }
}