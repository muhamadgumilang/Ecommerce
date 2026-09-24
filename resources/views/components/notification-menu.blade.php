@auth
    @php
        $notificationItems = collect();
        $notificationCount = 0;

        if (Auth::user()->isSeller()) {
            $notificationCount = \App\Models\Order::where('order_status', 'Processing')
                ->whereHas('orderDetails.product', fn ($query) => $query->where('seller_id', Auth::id()))
                ->count();
            $notificationItems = \App\Models\Order::where('order_status', 'Processing')
                ->whereHas('orderDetails.product', fn ($query) => $query->where('seller_id', Auth::id()))
                ->with('customer')
                ->latest('order_date')
                ->take(5)
                ->get()
                ->map(fn ($order) => [
                    'title' => 'Pesanan baru masuk',
                    'message' => 'Pesanan #' . $order->order_id . ' dari ' . ($order->customer?->name ?? 'pelanggan'),
                    'url' => route('seller.orders.show', $order),
                ]);
        } elseif (Auth::user()->isAdmin()) {
            $notificationCount = \App\Models\Payment::where('payment_status', 'Pending')->count();
            $notificationItems = \App\Models\Payment::where('payment_status', 'Pending')
                ->with('order.customer')
                ->latest()
                ->take(5)
                ->get()
                ->map(fn ($payment) => [
                    'title' => 'Pembayaran perlu diperiksa',
                    'message' => 'Pesanan #' . $payment->order_id . ' menunggu verifikasi',
                    'url' => route('admin.payments.index'),
                ]);
        } else {
            $notificationCount = Auth::user()->orders()
                ->whereIn('order_status', ['Processing', 'Shipped', 'Completed'])
                ->count();
            $notificationItems = Auth::user()->orders()
                ->whereIn('order_status', ['Processing', 'Shipped', 'Completed'])
                ->latest('order_date')
                ->take(5)
                ->get()
                ->map(fn ($order) => [
                    'title' => match ($order->order_status) {
                        'Processing' => 'Pesanan sedang diproses',
                        'Shipped' => 'Pesanan sedang dikirim',
                        'Completed' => 'Pesanan selesai',
                        default => 'Pembaruan pesanan',
                    },
                    'message' => 'Pesanan #' . $order->order_id,
                    'url' => route('orders.show', $order),
                ]);
        }
    @endphp

    <details class="relative">
        <summary class="flex h-10 w-10 cursor-pointer list-none items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-blue-200 hover:text-blue-600">
            <span class="sr-only">Buka notifikasi</span>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.5-1.5V11a6.5 6.5 0 10-13 0v4.5L4 17h5m6 0a3 3 0 01-6 0" />
            </svg>
            @if ($notificationCount > 0)
                <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">
                    {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                </span>
            @endif
        </summary>

        <div class="absolute right-0 z-50 mt-3 w-80 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-slate-100 px-4 py-3">
                <h3 class="text-sm font-bold text-slate-900">Notifikasi</h3>
                @if ($notificationCount > 0)
                    <span class="text-xs font-medium text-blue-600">{{ $notificationCount }} pemberitahuan</span>
                @endif
            </div>

            @forelse ($notificationItems as $notification)
                <a href="{{ $notification['url'] }}" class="block border-b border-slate-100 px-4 py-3 transition hover:bg-blue-50">
                    <p class="text-xs font-semibold text-slate-800">{{ $notification['title'] }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $notification['message'] }}</p>
                </a>
            @empty
                <div class="px-4 py-8 text-center text-xs text-slate-400">Belum ada notifikasi baru.</div>
            @endforelse

            <a href="{{ Auth::user()->isSeller() ? route('seller.orders.index') : (Auth::user()->isAdmin() ? route('admin.payments.index') : route('orders.index')) }}"
                class="block bg-slate-50 px-4 py-3 text-center text-xs font-semibold text-blue-600 hover:bg-blue-100">
                Lihat semua
            </a>
        </div>
    </details>
@endauth
