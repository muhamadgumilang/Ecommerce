<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Admin Dashboard - E-Commerce') }}
            </h2>
            <a href="{{ route('home') }}" target="_blank" rel="noopener"
                class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
                <span>🛍️</span>
                <span>Lihat Toko</span>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500">Total Produk</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalProducts }}</div>
                    <div class="mt-1 text-xs text-green-600 font-semibold">Produk aktif dalam sistem</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500">Pesanan Masuk</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalOrders }}</div>
                    <div class="mt-1 text-xs text-blue-600 font-semibold">Seluruh pesanan pelanggan</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500">Menunggu Pembayaran</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $pendingPayments }}</div>
                    <div class="mt-1 text-xs text-amber-600 font-semibold">Perlu dipantau admin</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500">
                    <div class="text-sm font-medium text-gray-500">Pembayaran Terverifikasi</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">Rp
                        {{ number_format($verifiedRevenue, 0, ',', '.') }}</div>
                    <div class="mt-1 text-xs text-green-600 font-semibold">Total transaksi berhasil</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-violet-500">
                    <div class="text-sm font-medium text-gray-500">Total Pengguna</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalUsers }}</div>
                    <div class="mt-1 text-xs text-violet-600 font-semibold">Admin, petugas, dan customer</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-orange-500">
                    <div class="text-sm font-medium text-gray-500">Petugas / Customer</div>
                    <div class="mt-2 text-2xl font-bold text-gray-900">{{ $totalSellers }} / {{ $totalCustomers }}</div>
                    <div class="mt-1 text-xs text-orange-600 font-semibold">Pengguna berdasarkan peran</div>
                </div>
            </div>

            <!-- Panel Akses Cepat / Manajemen (Navigasi dengan Route yang Valid) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Akses Cepat</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-7 gap-4">
                        <!-- Kelola Produk -->
                        <a href="{{ route('admin.products.index') }}"
                            class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg text-center transition">
                            <span class="text-2xl">📦</span>
                            <h4 class="mt-2 font-semibold text-gray-700">Kelola Produk</h4>
                        </a>

                        <!-- Daftar Pesanan Admin -->
                        <a href="{{ route('admin.orders.index') }}"
                            class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg text-center transition">
                            <span class="text-2xl">📑</span>
                            <h4 class="mt-2 font-semibold text-gray-700">Daftar Pesanan</h4>
                        </a>

                        <a href="{{ route('admin.payments.index') }}"
                            class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg text-center transition">
                            <span class="text-2xl">💳</span>
                            <h4 class="mt-2 font-semibold text-gray-700">Pembayaran</h4>
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                            class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg text-center transition">
                            <span class="text-2xl">👥</span>
                            <h4 class="mt-2 font-semibold text-gray-700">Pengguna</h4>
                        </a>

                        <!-- Kategori -->
                        <a href="{{ route('admin.categories.index') }}"
                            class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg text-center transition">
                            <span class="text-2xl">🏷️</span>
                            <h4 class="mt-2 font-semibold text-gray-700">Kategori</h4>
                        </a>

                        <!-- Katalog Publik -->
                        <a href="{{ route('catalog.index') }}"
                            class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg text-center transition">
                            <span class="text-2xl">📁</span>
                            <h4 class="mt-2 font-semibold text-gray-700">Lihat Katalog</h4>
                        </a>

                        <a href="{{ route('home') }}" target="_blank" rel="noopener"
                            class="p-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg text-center transition">
                            <span class="text-2xl">🛍️</span>
                            <h4 class="mt-2 font-semibold text-blue-700">Lihat Toko</h4>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <section class="xl:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg border border-slate-100">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Pesanan Terbaru</h3>
                            <p class="text-xs text-slate-500 mt-1">Pantau pembayaran, pengiriman, dan status pesanan.
                            </p>
                        </div>
                        <a href="{{ route('admin.orders.index') }}"
                            class="text-sm font-semibold text-blue-600 hover:text-blue-700">Lihat semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[650px] text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                                <tr>
                                    <th class="px-6 py-3">Pesanan</th>
                                    <th class="px-6 py-3">Pelanggan</th>
                                    <th class="px-6 py-3">Total</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($recentOrders as $order)
                                    @php
                                        $statusLabels = [
                                            'Pending Payment' => 'Menunggu Pembayaran',
                                            'Processing' => 'Sudah Dibayar / Diproses',
                                            'Shipped' => 'Sedang Dikirim',
                                            'Completed' => 'Sudah Tiba',
                                            'Cancelled' => 'Dibatalkan',
                                        ];
                                        $statusStyles = [
                                            'Pending Payment' => 'bg-amber-50 text-amber-700',
                                            'Processing' => 'bg-blue-50 text-blue-700',
                                            'Shipped' => 'bg-indigo-50 text-indigo-700',
                                            'Completed' => 'bg-emerald-50 text-emerald-700',
                                            'Cancelled' => 'bg-rose-50 text-rose-700',
                                        ];
                                    @endphp
                                    <tr class="hover:bg-slate-50">
                                        <td class="px-6 py-3 font-semibold text-slate-800">#{{ $order->order_id }}<div
                                                class="text-xs font-normal text-slate-400">
                                                {{ $order->order_date?->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td class="px-6 py-3 text-slate-600">{{ $order->customer?->name ?? '-' }}</td>
                                        <td class="px-6 py-3 font-medium text-slate-800">Rp
                                            {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                        <td class="px-6 py-3"><span
                                                class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusStyles[$order->order_status] ?? 'bg-slate-100 text-slate-600' }}">{{ $statusLabels[$order->order_status] ?? $order->order_status }}</span>
                                        </td>
                                        <td class="px-6 py-3"><a href="{{ route('admin.orders.show', $order) }}"
                                                class="font-semibold text-blue-600 hover:underline">Detail</a></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-400">Belum ada
                                            pesanan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>

                <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-slate-100">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Stok Menipis</h3>
                            <p class="text-xs text-slate-500 mt-1">Produk dengan stok 5 unit atau kurang.</p>
                        </div>
                        <a href="{{ route('admin.products.index') }}"
                            class="text-sm font-semibold text-blue-600 hover:text-blue-700">Kelola</a>
                    </div>
                    <div class="p-6 space-y-4">
                        @forelse ($lowStockProducts as $product)
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-semibold text-slate-800">
                                        {{ $product->product_name }}</p>
                                    <p class="truncate text-xs text-slate-400">
                                        {{ $product->seller?->name ?? 'Tanpa petugas' }}</p>
                                </div>
                                <span
                                    class="shrink-0 rounded-full px-2.5 py-1 text-xs font-bold {{ $product->stock === 0 ? 'bg-rose-50 text-rose-700' : 'bg-amber-50 text-amber-700' }}">{{ $product->stock }}
                                    unit</span>
                            </div>
                        @empty
                            <p class="py-8 text-center text-sm text-emerald-600">Semua stok masih aman.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <section class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Ringkasan Status Pesanan</h3>
                        <p class="text-xs text-slate-500 mt-1">Distribusi pesanan saat ini.</p>
                    </div><a href="{{ route('admin.orders.index') }}"
                        class="text-sm font-semibold text-blue-600 hover:text-blue-700">Kelola pesanan</a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                    @foreach ([
        'Pending Payment' => ['Menunggu Bayar', 'text-amber-700', 'bg-amber-50'],
        'Processing' => ['Diproses', 'text-blue-700', 'bg-blue-50'],
        'Shipped' => ['Dikirim', 'text-indigo-700', 'bg-indigo-50'],
        'Completed' => ['Selesai', 'text-emerald-700', 'bg-emerald-50'],
        'Cancelled' => ['Dibatalkan', 'text-rose-700', 'bg-rose-50'],
    ] as $status => [$label, $textColor, $background])
                        <div class="{{ $background }} rounded-xl p-4">
                            <p class="text-xs font-semibold {{ $textColor }}">{{ $label }}</p>
                            <p class="mt-1 text-2xl font-bold text-slate-800">{{ $statusCounts[$status] ?? 0 }}</p>
                        </div>
                    @endforeach
                </div>
            </section>

        </div>
    </div>
</x-app-layout>
