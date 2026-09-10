<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Produk</title>

    <!-- Fonts & Tailwind CSS (Sesuaikan dengan setup aset projectmu, misal Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-900 antialiased min-h-screen flex flex-col justify-between">

    <!-- Navbar Sederhana (Publik) -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-bold text-lg text-blue-600">
                Zenthercraft
            </a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}"
                    class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">Beranda</a>
                <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-blue-600">Katalog</a>

                @auth
                    <a href="{{ route('cart.index') }}"
                        class="text-xl leading-none text-slate-600 hover:text-blue-600 transition" title="Keranjang"
                        aria-label="Keranjang">&#128722;</a>
                    <a href="{{ url('/dashboard') }}"
                        class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-xl leading-none text-slate-600 hover:text-blue-600 transition" title="Keranjang"
                        aria-label="Keranjang">&#128722;</a>
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition">Daftar</a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-12 flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header Section -->
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">Katalog Produk</h1>
                    <p class="text-sm text-slate-500 mt-1">Temukan berbagai produk pilihan terbaik kami di sini.</p>
                </div>
                <div
                    class="text-sm font-medium text-slate-600 bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-200">
                    Total Produk: <span class="text-blue-600 font-bold">{{ $products->total() }}</span>
                </div>
            </div>

            <!-- Grid Produk -->
            @if ($products->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                    <div class="text-slate-400 text-base font-medium">Belum ada produk yang tersedia di katalog saat
                        ini.</div>
                    <p class="text-slate-400 text-xs mt-1">Silakan kembali lagi nanti.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $item)
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition flex flex-col">
                            <!-- Gambar Produk -->
                            <div class="h-48 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->product_name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <span class="text-xs text-slate-400 font-medium">No Image</span>
                                @endif
                            </div>

                            <!-- Detail Produk -->
                            <div class="p-5 flex-1 flex flex-col justify-between">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base line-clamp-1">
                                        {{ $item->product_name }}</h3>
                                    <p class="text-slate-500 text-xs mt-1.5 line-clamp-2">
                                        {{ $item->description ?? 'Tidak ada deskripsi.' }}</p>
                                </div>

                                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between">
                                    <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </span>
                                    <a href="{{ route('products.show', $item) }}"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-base rounded-xl transition shadow-sm shadow-blue-100"
                                        title="Lihat detail produk" aria-label="Lihat detail produk">
                                        &#128065;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginasi -->
                <div class="mt-8">
                    {{ $products->links() }}
                </div>
            @endif

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} Zenthercraft. All rights reserved.
    </footer>
</body>

</html>
