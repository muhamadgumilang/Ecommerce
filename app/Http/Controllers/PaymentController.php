<?php

namespace App\Http\Controllers;

<<<<<<< Updated upstream
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak mengakses pembayaran order ini.');

        if ($order->payment()->exists()) {
            return redirect()->route('orders.index')->with('error', 'Order ini sudah memiliki pembayaran.');
        }

        return view('payments.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak melakukan pembayaran untuk order ini.');

        $fields = $request->validate([
            'payment_method' => 'required|string|max:50',
        ]);

        if ($order->payment()->exists()) {
            return redirect()->route('orders.index')->with('error', 'Order ini sudah memiliki pembayaran.');
        }

        Payment::create([
            'order_id' => $order->order_id,
            'payment_method' => $fields['payment_method'],
            'payment_status' => 'Pending',
            'payment_date' => now(),
        ]);

        return redirect()->route('orders.index')->with('success', 'Pembayaran berhasil dikirim! Menunggu verifikasi admin.');
    }
}
=======
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
}
>>>>>>> Stashed changes
