<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function create(Order $order)
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak mengakses pembayaran order ini.');

        $payment = $order->payment;
        if ($payment?->payment_status === 'Verified') {
            return redirect()->route('orders.show', $order)->with('success', 'Pembayaran pesanan ini sudah berhasil dikonfirmasi.');
        }

        if ($payment && $payment->payment_status !== 'Failed' && filled($payment->snap_token)) {
            $order->load(['orderDetails.product', 'checkout', 'customer']);
            return view('payments.create', compact('order', 'payment'));
        }

        $order->load(['orderDetails.product', 'checkout', 'customer']);
        if ($payment?->payment_status === 'Failed' && $payment->stock_released) {
            $this->reserveOrderStock($order);
            $payment->stock_released = false;
            $payment->save();
        }

        $this->configureMidtrans();
        abort_if($order->orderDetails->isEmpty(), 422, 'Order belum memiliki detail produk.');

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
            'stock_released' => false,
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

    public function sync(Request $request, Order $order)
    {
        abort_unless(Auth::id() === $order->customer_id, 403, 'Anda tidak berhak menyinkronkan pembayaran order ini.');

        $validated = $request->validate([
            'order_id' => ['required', 'string', 'regex:/^ORDER-' . $order->order_id . '-\d+$/'],
        ]);

        $this->configureMidtrans();
        $transaction = Transaction::status($validated['order_id']);
        $transactionStatus = (string) ($transaction->transaction_status ?? 'pending');

        $paymentStatus = match ($transactionStatus) {
            'capture', 'settlement' => 'Verified',
            'deny', 'cancel', 'expire', 'failure' => 'Failed',
            default => 'Pending',
        };

        $order->load(['orderDetails.product', 'payment']);
        $payment = $order->payment;
        $wasAlreadyFailed = $payment?->payment_status === 'Failed' && $payment->stock_released;

        if ($paymentStatus === 'Failed' && ! $wasAlreadyFailed) {
            $this->releaseOrderStock($order);
        }

        $order->payment()->updateOrCreate(
            ['order_id' => $order->order_id],
            [
                'transaction_id' => $transaction->transaction_id ?? null,
                'payment_method' => $transaction->payment_type ?? 'Midtrans Snap',
                'payment_status' => $paymentStatus,
                'payment_date' => now(),
                'stock_released' => $paymentStatus === 'Failed',
            ]
        );

        if ($paymentStatus === 'Verified') {
            $order->update(['order_status' => 'Processing']);
        } elseif ($paymentStatus === 'Failed') {
            $order->update(['order_status' => 'Pending Payment']);
        }

        return response()->json([
            'payment_status' => $paymentStatus,
            'order_status' => $order->fresh()->order_status,
        ]);
    }

    public function notification(Request $request)
    {
        $this->configureMidtrans();

        $notification = new Notification();
        $midtransOrderId = (string) ($notification->order_id ?? '');
        if (! preg_match('/^ORDER-(\d+)-\d+$/', $midtransOrderId, $matches)) {
            return response()->json(['message' => 'Format order ID Midtrans tidak valid.'], 422);
        }

        $orderId = $matches[1];
        $order = Order::find($orderId);

        if (! $order) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }

        $status = match (true) {
            in_array($notification->transaction_status, ['capture', 'settlement'], true) => 'Verified',
            in_array($notification->transaction_status, ['deny', 'cancel', 'expire', 'failure'], true) => 'Failed',
            default => 'Pending',
        };

        $payment = $order->payment;
        $wasAlreadyFailed = $payment?->payment_status === 'Failed' && $payment->stock_released;

        if ($status === 'Failed' && ! $wasAlreadyFailed) {
            $this->releaseOrderStock($order);
        }

        $order->payment()->updateOrCreate(
            ['order_id' => $order->order_id],
            [
                'transaction_id' => $notification->transaction_id,
                'payment_method' => $notification->payment_type ?? 'Midtrans Snap',
                'payment_status' => $status,
                'payment_date' => now(),
                'stock_released' => $status === 'Failed',
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
        $serverKey = config('services.midtrans.server_key');
        abort_if(blank($serverKey), 500, 'MIDTRANS_SERVER_KEY belum dikonfigurasi.');

        Config::$serverKey = $serverKey;
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool) config('services.midtrans.is_3ds');
    }

    private function reserveOrderStock(Order $order): void
    {
        DB::transaction(function () use ($order): void {
            foreach ($order->orderDetails as $detail) {
                $product = $detail->product()->lockForUpdate()->first();

                if (! $product || $detail->quantity > $product->stock) {
                    throw ValidationException::withMessages([
                        'stock' => "Stok produk " . ($product?->product_name ?? 'yang dipilih') . ' tidak mencukupi untuk pembayaran ulang.',
                    ]);
                }

                $product->decrement('stock', $detail->quantity);
            }
        });
    }

    private function releaseOrderStock(Order $order): void
    {
        DB::transaction(function () use ($order): void {
            foreach ($order->orderDetails as $detail) {
                $detail->product()->lockForUpdate()->first()?->increment('stock', $detail->quantity);
            }
        });
    }
}
