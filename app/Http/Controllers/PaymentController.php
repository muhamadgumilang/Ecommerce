<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak mengakses pembayaran order ini.');

        $payment = $order->payment;
        if ($payment && $payment->payment_status !== 'Failed') {
            $order->load(['orderDetails.product', 'checkout', 'customer']);
            return view('payments.create', compact('order', 'payment'));
        }

        $order->load(['orderDetails.product', 'checkout', 'customer']);
        $this->configureMidtrans();
        $midtransOrderId = 'ORDER-' . $order->order_id . '-' . now()->timestamp;
        $itemDetails = $order->orderDetails->map(function ($detail) {
            $quantity = (int) $detail->quantity;
            $lineTotal = (int) round((float) $detail->subtotal);

            return [
                'id' => (string) $detail->product_id,
                'price' => intdiv($lineTotal, max($quantity, 1)),
                'quantity' => $quantity,
                'name' => $detail->product?->product_name ?: 'Produk #' . $detail->product_id,
            ];
        })->values()->all();

        $grossAmount = (int) round((float) $order->total_amount);

        $itemTotal = array_sum(array_map(
            fn (array $item) => $item['price'] * $item['quantity'],
            $itemDetails
        ));

        if ($order->checkout?->shipping_fee > 0) {
            $itemDetails[] = [
                'id' => 'shipping',
                'price' => (int) round($order->checkout->shipping_fee),
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
            $itemTotal += (int) round($order->checkout->shipping_fee);
        }

        $roundingDifference = $grossAmount - $itemTotal;
        if ($roundingDifference !== 0) {
            $itemDetails[] = [
                'id' => 'rounding-adjustment',
                'price' => $roundingDifference,
                'quantity' => 1,
                'name' => 'Penyesuaian Pembulatan',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->customer->name,
                'email' => $order->customer->email,
            ],
        ];

        $payment = $payment ?: new Payment(['order_id' => $order->order_id]);
        $payment->fill([
            'payment_method' => 'Midtrans Snap',
            'payment_status' => 'Pending',
            'payment_date' => now(),
            'snap_token' => Snap::getSnapToken($params),
        ]);
        $payment->save();

        return view('payments.create', compact('order', 'payment'));
    }

    public function store(Request $request, Order $order)
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak melakukan pembayaran untuk order ini.');

        return redirect()->route('payments.create', $order);
    }

    public function notification(Request $request)
    {
        $this->configureMidtrans();

        $notification = new Notification();
        $orderId = explode('-', str_replace('ORDER-', '', $notification->order_id))[0];
        $order = Order::find($orderId);

        abort_unless($order, 404, 'Order tidak ditemukan.');

        $status = match (true) {
            in_array($notification->transaction_status, ['capture', 'settlement'], true) => 'Verified',
            in_array($notification->transaction_status, ['deny', 'cancel', 'expire', 'failure'], true) => 'Failed',
            default => 'Pending',
        };

        $order->payment()->updateOrCreate(
            ['order_id' => $order->order_id],
            [
                'transaction_id' => $notification->transaction_id,
                'payment_method' => $notification->payment_type ?? 'Midtrans Snap',
                'payment_status' => $status,
                'payment_date' => now(),
            ]
        );

        if ($status === 'Verified') {
            $order->update(['order_status' => 'Processing']);
        } elseif ($status === 'Failed') {
            $order->update(['order_status' => 'Pending Payment']);
        }

        return response()->json(['message' => 'Notification processed.']);
    }

    private function configureMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool) config('services.midtrans.is_3ds');
    }
}
