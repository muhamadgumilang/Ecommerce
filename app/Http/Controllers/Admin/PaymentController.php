<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['order.customer', 'admin'])
            ->latest('payment_date')
            ->latest('payment_id')
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:Pending,Verified,Failed',
        ]);

        DB::transaction(function () use ($payment, $validated): void {
            $payment->update([
                'payment_status' => $validated['payment_status'],
                'admin_id' => auth()->user()->user_id,
                'payment_date' => $payment->payment_date ?: now(),
            ]);

            $orderStatus = match ($validated['payment_status']) {
                'Verified' => 'Processing',
                'Pending', 'Failed' => 'Pending Payment',
            };

            $payment->order()->update(['order_status' => $orderStatus]);
        });

        return redirect()->route('admin.payments.index')
            ->with('success', "Status pembayaran pesanan #{$payment->order_id} berhasil diperbarui.");
    }
}
