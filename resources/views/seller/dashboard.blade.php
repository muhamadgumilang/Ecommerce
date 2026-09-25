<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800">
                    {{ __('Dashboard Seller') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Selamat datang kembali, <strong class="text-blue-600">{{ Auth::user()->name }}</strong>! Kelola
                    produk dan pesanan toko Anda di sini.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}"
                    class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-2">
                    <span>🛍️</span> {{ __('Lihat Toko') }}
                </a>
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
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
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

                <!-- Kategori dari Admin -->
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-slate-500">Kategori Tersedia</div>
                        <div class="mt-2 text-3xl font-bold text-slate-900">{{ $totalCategories }}</div>
                        <span class="mt-2 text-xs text-slate-400 block">Master kategori dikelola Admin</span>
                    </div>
                    <div
                        class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-2xl font-bold">
                        🏷️
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

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <section class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <h3 class="text-base font-bold text-slate-800">Laporan Penjualan</h3>
                    <p class="mt-1 text-xs text-slate-500">Omset toko 6 bulan terakhir.</p>
                    @php($maxMonthlySales = max(1, $monthlySales->max('total')))
                    <div class="mt-5 space-y-4">
                        @foreach ($monthlySales as $month)
                            <div>
                                <div class="mb-1 flex justify-between text-xs">
                                    <span class="font-medium text-slate-500">{{ $month['label'] }}</span>
                                    <span class="font-semibold text-slate-700">Rp
                                        {{ number_format($month['total'], 0, ',', '.') }}</span>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full bg-emerald-500"
                                        style="width: {{ ($month['total'] / $maxMonthlySales) * 100 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                    <h3 class="text-base font-bold text-slate-800">Produk Terlaris</h3>
                    <p class="mt-1 text-xs text-slate-500">Produk toko dengan penjualan terbanyak.</p>
                    <div class="mt-5 space-y-4">
                        @forelse ($topProducts as $item)
                            <div class="flex items-center justify-between gap-4">
                                <p class="truncate text-sm font-semibold text-slate-800">
                                    {{ $item->product?->product_name ?? 'Produk dihapus' }}</p>
                                <span
                                    class="shrink-0 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700">{{ $item->total_quantity }}
                                    terjual</span>
                            </div>
                        @empty
                            <p class="py-8 text-center text-sm text-slate-400">Belum ada penjualan terverifikasi.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Pintasan Cepat -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="text-base font-bold text-slate-800 mb-4">Navigasi Pengelolaan Toko</h3>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
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

                    <a href="{{ route('seller.categories.index') }}"
                        class="p-4 bg-slate-50 hover:bg-blue-50 hover:border-blue-200 border border-slate-200 rounded-xl text-center transition group">
                        <span class="text-2xl group-hover:scale-110 transition inline-block">🏷️</span>
                        <h4 class="mt-2 text-sm font-semibold text-slate-700 group-hover:text-blue-600">Kategori Saya
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

                <div class="p-6 text-sm text-slate-600">
                    @if (!empty($recentOrders) && $recentOrders->count() > 0)
                        <div class="space-y-3">
                            @foreach ($recentOrders->take(5) as $order)
                                <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                                    <div>
                                        <div class="font-semibold text-slate-800">#{{ $order->order_id }}</div>
                                        <div class="mt-1 text-xs text-slate-500">{{ $order->customer?->name ?? '-' }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-slate-500">{{ $order->order_status }}</div>
                                        <a href="{{ route('seller.orders.show', $order) }}"
                                            class="mt-1 inline-block text-xs font-semibold text-blue-600 hover:text-blue-700">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center text-slate-400">
                            Belum ada pesanan masuk untuk produk toko Anda.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
