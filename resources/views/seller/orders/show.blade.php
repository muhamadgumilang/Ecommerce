<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('seller.orders.index') }}" class="text-slate-400 hover:text-slate-600 transition text-sm">
                    &larr; Kembali
                </a>
                <h2 class="font-bold text-2xl text-slate-800">
                    {{ __('Detail Pesanan #') }}{{ $order->order_id }}
                </h2>
            </div>
            <div>
                @php
                    $stClass = match($order->order_status) {
                        'Completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'Shipped' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'Processing' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                        default => 'bg-slate-50 text-slate-600 border-slate-200',
                    };
                @endphp
                <span class="px-3.5 py-1.5 text-xs font-bold rounded-full border {{ $stClass }}">
                    Status: {{ $order->order_status }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm flex items-center gap-3">
                    <span class="text-emerald-500 text-lg">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Banner Aksi Pengiriman jika status Processing -->
            @if ($order->order_status === 'Processing')
                <div class="bg-gradient-to-r from-blue-600 to-sky-600 rounded-2xl p-6 text-white shadow-md flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-lg">Pesanan Siap Dikirim!</h3>
                        <p class="text-sm text-blue-100 mt-1">
                            Pembayaran telah dikonfirmasi. Silakan packing produk dan serahkan ke kurir pengiriman.
                        </p>
                    </div>
                    <form action="{{ route('seller.orders.ship', $order) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa barang pesanan ini sudah dikirimkan via kurir?')">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-5 py-2.5 bg-white text-blue-700 hover:bg-blue-50 rounded-xl font-bold text-sm shadow transition whitespace-nowrap">
                            🚚 Tandai Sudah Dikirim (Shipped)
                        </button>
                    </form>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- KIRI: Produk Pesanan dari Toko Anda (2 Kolom) -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <h3 class="font-bold text-base text-slate-800 mb-4 flex items-center gap-2">
                            <span>📦</span> Produk Pesanan dari Toko Anda
                        </h3>

                        <div class="divide-y divide-slate-100">
                            @php $sellerSubtotal = 0; @endphp
                            @foreach ($order->orderDetails as $detail)
                                @php $sellerSubtotal += $detail->subtotal; @endphp
                                <div class="py-4 first:pt-0 last:pb-0 flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 shrink-0 flex items-center justify-center">
                                        @if ($detail->product?->image)
                                            <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-[10px] text-slate-400">No Image</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-slate-800 text-sm truncate">
                                            {{ $detail->product?->product_name ?? 'Produk Dihapus' }}
                                        </h4>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            Harga Satuan: Rp {{ number_format($detail->product?->price ?? 0, 0, ',', '.') }}
                                        </p>
                                        <p class="text-xs font-medium text-blue-600 mt-1">
                                            Jumlah: {{ $detail->quantity }} item
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-slate-800 text-sm">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-sm font-medium text-slate-500">Subtotal Produk Toko Anda:</span>
                            <span class="text-lg font-bold text-emerald-600">
                                Rp {{ number_format($sellerSubtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- KANAN: Data Pembeli, Alamat & Pembayaran (1 Kolom) -->
                <div class="space-y-6">

                    <!-- Info Pembeli -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <h3 class="font-bold text-sm text-slate-800 mb-3 flex items-center gap-2">
                            <span>👤</span> Informasi Pembeli
                        </h3>
                        <div class="text-sm space-y-1.5">
                            <div class="font-semibold text-slate-800">{{ $order->customer?->name ?? 'Customer' }}</div>
                            <div class="text-xs text-slate-500">{{ $order->customer?->email ?? '-' }}</div>
                            <div class="text-xs text-slate-500">No. HP: {{ $order->customer?->phone ?? '-' }}</div>
                        </div>
                    </div>

                    <!-- Info Alamat & Pengiriman -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <h3 class="font-bold text-sm text-slate-800 mb-3 flex items-center gap-2">
                            <span>📍</span> Alamat & Kurir Pengiriman
                        </h3>
                        @if ($order->checkout)
                            <div class="text-sm space-y-2">
                                <div>
                                    <span class="text-xs text-slate-400 block font-medium">Alamat Penerima:</span>
                                    <p class="text-xs text-slate-700 mt-0.5 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        {{ $order->checkout->shipping_address }}
                                    </p>
                                </div>
                                <div class="pt-2 flex justify-between items-center text-xs">
                                    <span class="text-slate-500">Kurir:</span>
                                    <span class="font-bold text-slate-800">{{ $order->checkout->courier }}</span>
                                </div>
                                @if ($order->checkout->notes)
                                    <div class="pt-2 border-t border-slate-100">
                                        <span class="text-xs text-slate-400 block">Catatan Pembeli:</span>
                                        <p class="text-xs italic text-slate-600 mt-0.5">"{{ $order->checkout->notes }}"</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <p class="text-xs text-slate-400">Data checkout belum tersedia.</p>
                        @endif
                    </div>

                    <!-- Status Pembayaran -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <h3 class="font-bold text-sm text-slate-800 mb-3 flex items-center gap-2">
                            <span>💳</span> Status Pembayaran
                        </h3>
                        @if ($order->payment)
                            <div class="space-y-2 text-xs">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Metode:</span>
                                    <span class="font-medium text-slate-800">{{ $order->payment->payment_method }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Status Bayar:</span>
                                    <span class="px-2 py-0.5 rounded-full font-bold {{ $order->payment->payment_status === 'Verified' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                        {{ $order->payment->payment_status }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <p class="text-xs text-slate-400">Belum ada data pembayaran.</p>
                        @endif
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
