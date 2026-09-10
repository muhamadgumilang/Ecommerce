<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard - E-Commerce') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Produk (Dinamis dari Database) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <div class="text-sm font-medium text-gray-500">Total Produk</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">{{ $totalProducts }}</div>
                    <div class="mt-1 text-xs text-green-600 font-semibold">Produk aktif dalam sistem</div>
                </div>

                <!-- Total Pesanan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500">Pesanan Masuk</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">45</div>
                    <div class="mt-1 text-xs text-blue-600 font-semibold">Menunggu konfirmasi pembayaran</div>
                </div>

                <!-- Total Pendapatan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500">Pendapatan (Bulan Ini)</div>
                    <div class="mt-2 text-3xl font-bold text-gray-900">Rp 12.500.000</div>
                    <div class="mt-1 text-xs text-green-600 font-semibold">+8% dari bulan lalu</div>
                </div>
            </div>

            <!-- Panel Akses Cepat / Manajemen (Navigasi dengan Route yang Valid) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Navigasi Pengelolaan Admin</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <!-- Kelola Produk -->
                        <a href="{{ route('admin.products.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg text-center transition">
                            <span class="text-2xl">📦</span>
                            <h4 class="mt-2 font-semibold text-gray-700">Kelola Produk</h4>
                        </a>

                        <!-- Daftar Pesanan Admin -->
                        <a href="{{ route('admin.orders.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg text-center transition">
                            <span class="text-2xl">📑</span>
                            <h4 class="mt-2 font-semibold text-gray-700">Daftar Pesanan</h4>
                        </a>

                        <!-- Kategori -->
                        <a href="{{ route('admin.categories.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg text-center transition">
                            <span class="text-2xl">🏷️</span>
                            <h4 class="mt-2 font-semibold text-gray-700">Kategori</h4>
                        </a>

                        <!-- Katalog Admin / Opsi Lain -->
                        <a href="{{ route('catalog.index') }}" class="p-4 bg-gray-50 hover:bg-indigo-50 border border-gray-200 rounded-lg text-center transition">
                            <span class="text-2xl">📁</span>
                            <h4 class="mt-2 font-semibold text-gray-700">Manajemen Katalog</h4>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
