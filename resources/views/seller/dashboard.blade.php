<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800">
                    {{ __('Dashboard Petugas') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Selamat datang kembali, <strong class="text-blue-600">{{ Auth::user()->name }}</strong>! Kelola
                    produk dan pesanan toko Anda di sini.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('seller.products.create') }}"
                    class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-2">
                    <span>+</span> {{ __('Tambah Produk') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm flex items-center gap-3">
                    <span class="text-emerald-500 text-lg">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Statistik Toko Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Produk -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500">Produk Kelolaan Saya</div>
                        <div class="mt-2 text-3xl font-bold text-slate-900">{{ $totalProducts }}</div>
                        <a href="{{ route('seller.products.index') }}"
                            class="mt-2 text-xs font-semibold text-blue-600 hover:text-blue-700 inline-block">
                            Lihat semua produk &rarr;
                        </a>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl font-bold">
                        📦
                    </div>
                </div>

                <!-- Pesanan Masuk -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500">Pesanan Masuk</div>
                        <div class="mt-2 text-3xl font-bold text-slate-900">{{ $incomingOrdersCount }}</div>
                        <a href="{{ route('seller.orders.index') }}"
                            class="mt-2 text-xs font-semibold text-amber-600 hover:text-amber-700 inline-block">
                            Kelola pesanan masuk &rarr;
                        </a>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl font-bold">
                        📑
                    </div>
                </div>

                <!-- Total Pendapatan -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500">Total Omset</div>
                        <div class="mt-2 text-3xl font-bold text-emerald-600">Rp
                            {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        <span class="mt-2 text-xs text-slate-400 block">Dari pesanan terbayar & selesai</span>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl font-bold">
                        💰
                    </div>
                </div>
            </div>

            <!-- Pintasan Cepat -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-base font-bold text-slate-800 mb-4">Navigasi Pengelolaan Toko</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <a href="{{ route('seller.products.create') }}"
                        class="p-4 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 border border-slate-200 rounded-xl text-center transition group">
                        <span class="text-2xl group-hover:scale-110 transition inline-block">➕</span>
                        <h4 class="mt-2 text-sm font-semibold text-slate-700 group-hover:text-blue-600">Tambah Produk
                        </h4>
                    </a>

                    <a href="{{ route('seller.products.index') }}"
                        class="p-4 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 border border-slate-200 rounded-xl text-center transition group">
                        <span class="text-2xl group-hover:scale-110 transition inline-block">📦</span>
                        <h4 class="mt-2 text-sm font-semibold text-slate-700 group-hover:text-blue-600">Produk Kelolaan
                        </h4>
                    </a>

                    <a href="{{ route('seller.orders.index') }}"
                        class="p-4 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 border border-slate-200 rounded-xl text-center transition group">
                        <span class="text-2xl group-hover:scale-110 transition inline-block">🚚</span>
                        <h4 class="mt-2 text-sm font-semibold text-slate-700 group-hover:text-blue-600">Pesanan Masuk
                        </h4>
                    </a>

                    <a href="{{ route('catalog.index') }}"
                        class="p-4 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 border border-slate-200 rounded-xl text-center transition group">
                        <span class="text-2xl group-hover:scale-110 transition inline-block">🛍️</span>
                        <h4 class="mt-2 text-sm font-semibold text-slate-700 group-hover:text-blue-600">Lihat Katalog
                        </h4>
                    </a>
                </div>
            </div>

            <!-- Pesanan Terbaru Masuk ke Toko -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-slate-800">Pesanan Terbaru untuk Toko Anda</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Pesanan dari pembeli yang berisi produk toko Anda</p>
                    </div>
                    <a href="{{ route('seller.orders.index') }}"
                        class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[700px]">
                        <thead class="bg-slate-50 text-slate-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="py-3.5 px-6">ID Pesanan</th>
                                <th class="py-3.5 px-6">Pembeli</th>
                                <th class="py-3.5 px-6">Tanggal</th>
                                <th class="py-3.5 px-6">Item Toko Anda</th>
                                <th class="py-3.5 px-6">Status</th>
                                <th class="py-3.5 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($recentOrders as $order)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-4 px-6 font-semibold text-slate-800">#{{ $order->order_id }}</td>
                                    <td class="py-4 px-6 text-slate-600">{{ $order->customer?->name ?? '-' }}</td>
                                    <td class="py-4 px-6 text-slate-500 text-xs">
                                        {{ $order->order_date ? $order->order_date->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="space-y-1">
                                            @foreach ($order->orderDetails as $detail)
                                                <div class="text-xs text-slate-700">
                                                    <span
                                                        class="font-medium">{{ $detail->product?->product_name }}</span>
                                                    <span class="text-slate-400">× {{ $detail->quantity }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $stClass = match ($order->order_status) {
                                                'Completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Shipped' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'Processing' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default => 'bg-slate-50 text-slate-600 border-slate-200',
                                            };
                                        @endphp
                                        <span
                                            class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full border {{ $stClass }}">
                                            {{ $order->order_status }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('seller.orders.show', $order) }}"
                                            class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-xs font-semibold transition inline-block">
                                            Detail & Proses
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-10 text-center text-slate-400 text-sm">
                                        Belum ada pesanan masuk untuk produk toko Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
