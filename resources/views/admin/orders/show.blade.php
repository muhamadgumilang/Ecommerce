<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-slate-800 leading-tight">Detail Pesanan #{{ $order->order_id }}</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Dibuat pada {{ ($order->order_date ?? $order->created_at)->translatedFormat('d F Y, H:i') }} WIB</p>
                </div>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('admin.orders.edit', $order) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-semibold hover:bg-blue-700 shadow-sm shadow-blue-500/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Ubah Status</span>
                </a>
                <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-semibold hover:bg-slate-200 transition">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Message -->
            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm shadow-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Quick Status Banner -->
            <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">
                        #{{ $order->order_id }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-slate-800">Status Pesanan:</span>
                            @php
                                $statusStyles = [
                                    'Pending Payment' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    'Shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    'Completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $statusStyles[$order->order_status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $order->order_status }}
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 mt-1">Total Tagihan: <strong class="text-blue-600 font-bold text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="flex items-center gap-2">
                    @csrf
                    @method('PUT')
                    <label for="quick_status" class="text-xs font-medium text-slate-500">Ubah Cepat:</label>
                    <select id="quick_status" name="order_status" class="text-xs rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-1.5 pl-3 pr-8">
                        @foreach (['Pending Payment', 'Processing', 'Shipped', 'Completed', 'Cancelled'] as $status)
                            <option value="{{ $status }}" @selected($order->order_status === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-3 py-1.5 bg-slate-800 text-white rounded-xl text-xs font-medium hover:bg-slate-900 transition">
                        Update
                    </button>
                </form>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                <!-- Left 2 Cols: Order Items & Shipping -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Detail Produk Card -->
                    <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                            <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span>Detail Produk</span>
                            </h3>
                            <span class="text-xs text-slate-500">{{ $order->orderDetails->count() }} item</span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse ($order->orderDetails as $detail)
                                <div class="py-3.5 first:pt-0 last:pb-0 flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($detail->product && $detail->product->image)
                                            <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl text-slate-400">&#128722;</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-slate-900 text-sm truncate">
                                            {{ $detail->product?->product_name ?? 'Produk Dihapus' }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-1">
                                            Rp {{ number_format($detail->product?->price ?? ($detail->subtotal / max(1, $detail->quantity)), 0, ',', '.') }} &times; {{ $detail->quantity }} item
                                        </p>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <p class="font-bold text-slate-900 text-sm">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center py-6 text-slate-400 text-sm">Tidak ada detail produk.</p>
                            @endforelse
                        </div>

                        <div class="border-t border-slate-100 pt-4 mt-4 space-y-2 text-sm">
                            <div class="flex justify-between text-slate-500 text-xs">
                                <span>Subtotal Barang:</span>
                                <span class="font-medium text-slate-800">Rp {{ number_format($order->orderDetails->sum('subtotal'), 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-slate-500 text-xs">
                                <span>Ongkos Kirim:</span>
                                <span class="font-medium text-slate-800">
                                    {{ ($order->checkout?->shipping_fee ?? 0) > 0 ? 'Rp ' . number_format($order->checkout->shipping_fee, 0, ',', '.') : 'Gratis' }}
                                </span>
                            </div>
                            <div class="flex justify-between font-bold text-slate-900 text-base pt-2 border-t border-slate-100">
                                <span>Total Tagihan:</span>
                                <span class="text-blue-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pengiriman Card -->
                    <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6">
                        <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 border-b border-slate-100 pb-4 mb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <span>Informasi Pengiriman</span>
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-slate-400 font-medium uppercase">Kurir / Metode</p>
                                <p class="font-semibold text-slate-800 mt-1">{{ $order->checkout?->courier ?? 'Pengiriman standar' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-medium uppercase">Biaya Kirim</p>
                                <p class="font-semibold text-slate-800 mt-1">Rp {{ number_format($order->checkout?->shipping_fee ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs text-slate-400 font-medium uppercase">Alamat Pengiriman</p>
                                <p class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-slate-700 mt-1.5 leading-relaxed">
                                    {{ $order->checkout?->shipping_address ?? '-' }}
                                </p>
                            </div>
                            @if ($order->checkout?->notes)
                                <div class="sm:col-span-2">
                                    <p class="text-xs text-slate-400 font-medium uppercase">Catatan Pembeli</p>
                                    <p class="bg-amber-50/60 p-3 rounded-xl border border-amber-100 text-amber-800 italic text-xs mt-1.5">
                                        "{{ $order->checkout->notes }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <!-- Right 1 Col: Customer & Payment -->
                <div class="space-y-6">

                    <!-- Customer Info Card -->
                    <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6">
                        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 mb-4">
                            Data Pelanggan
                        </h3>

                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-500 to-sky-400 flex items-center justify-center font-bold text-white shadow-sm">
                                {{ strtoupper(substr($order->customer?->name ?? 'P', 0, 1)) }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="font-semibold text-slate-800 text-sm truncate">{{ $order->customer?->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $order->customer?->email ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="space-y-2 text-xs border-t border-slate-100 pt-3 text-slate-600">
                            <div class="flex justify-between">
                                <span class="text-slate-400">ID Pelanggan:</span>
                                <span class="font-medium text-slate-700">#{{ $order->customer_id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-400">Role:</span>
                                <span class="font-medium text-slate-700">{{ $order->customer?->role ?? 'Customer' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info Card -->
                    <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6">
                        <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 mb-4">
                            Status Pembayaran
                        </h3>

                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-xs text-slate-400 font-medium uppercase">Metode</p>
                                <p class="font-semibold text-slate-800 mt-1">{{ $order->payment?->payment_method ?? 'Belum memilih metode' }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400 font-medium uppercase">Status</p>
                                <div class="mt-1">
                                    @if ($order->payment)
                                        @php
                                            $payStyles = [
                                                'Verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Failed' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $payStyles[$order->payment->payment_status] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ $order->payment->payment_status }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            Belum Ada Data Pembayaran
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400 font-medium uppercase">Waktu Pembayaran</p>
                                <p class="text-slate-700 text-xs mt-1">
                                    {{ $order->payment?->payment_date ? \Carbon\Carbon::parse($order->payment->payment_date)->translatedFormat('d F Y, H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
