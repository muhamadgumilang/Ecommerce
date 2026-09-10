<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog Produk - {{ config('app.name', 'Zenthercraft') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-bold text-lg text-blue-600 flex items-center gap-2">
                <span class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm shadow-sm">Z</span>
                <span>Zenthercraft</span>
            </a>

            <nav class="flex items-center gap-4 sm:gap-6">
                <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                    Beranda
                </a>
                <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-blue-600">
                    Katalog
                </a>
                <a href="{{ route('orders.index') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                    Pesanan Saya
                </a>
                <a href="{{ route('cart.index') }}" class="text-xl leading-none text-slate-600 hover:text-blue-600 transition" title="Keranjang" aria-label="Keranjang">
                    &#128722;
                </a>

                @auth
                    @if (Auth::user()->role === 'Admin')
                        <a href="{{ url('/dashboard') }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                            Admin
                        </a>
                    @else
                        <span class="text-xs text-slate-500 hidden md:inline">Halo, <strong class="text-slate-800">{{ Auth::user()->name }}</strong></span>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-700 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition">
                            Daftar
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-8 sm:py-10 flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs -->
            <nav class="flex items-center text-sm text-slate-500 space-x-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                <span>&rsaquo;</span>
                <span class="text-slate-900 font-medium">Katalog Produk</span>
            </nav>

            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Katalog Produk</h1>
                    <p class="text-sm text-slate-500 mt-1">Temukan berbagai koleksi produk berkualitas terbaik dengan harga terjangkau.</p>
                </div>
                <div class="text-xs font-semibold text-slate-600 bg-slate-100 px-3.5 py-2 rounded-xl border border-slate-200">
                    Total: <span class="text-blue-600 font-bold">{{ $products->total() }} Produk</span>
                </div>
            </div>

            <!-- Grid Produk -->
            @if ($products->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-16 text-center max-w-xl mx-auto">
                    <div class="text-5xl mb-4">&#128230;</div>
                    <h2 class="text-lg font-bold text-slate-900">Belum Ada Produk Tersedia</h2>
                    <p class="text-sm text-slate-500 mt-1">Produk saat ini sedang dalam persiapan. Silakan periksa kembali nanti.</p>
                    <a href="{{ route('home') }}"
                       class="inline-block mt-6 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                        Kembali ke Beranda
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $item)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md hover:border-blue-300 transition duration-200 flex flex-col group">
                            <!-- Gambar Produk -->
                            <div class="h-52 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}"
                                         alt="{{ $item->product_name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <span class="text-3xl text-slate-300">&#128722;</span>
                                @endif

                                @if ($item->category)
                                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-blue-600 text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-sm border border-slate-100">
                                        {{ $item->category->category_name }}
                                    </span>
                                @endif
                            </div>

                            <!-- Detail Produk -->
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div>
                                    <h3 class="font-bold text-slate-900 text-base leading-snug line-clamp-1 group-hover:text-blue-600 transition">
                                        <a href="{{ route('products.show', $item) }}">
                                            {{ $item->product_name }}
                                        </a>
                                    </h3>
                                    <p class="text-slate-500 text-xs mt-1.5 line-clamp-2 leading-relaxed">
                                        {{ $item->description ?? 'Tidak ada deskripsi produk.' }}
                                    </p>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                                        <span>Stok:</span>
                                        @if ($item->stock > 0)
                                            <span class="text-emerald-600 font-semibold">{{ $item->stock }} unit</span>
                                        @else
                                            <span class="text-rose-500 font-semibold">Habis</span>
                                        @endif
                                    </div>

                                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                        <div>
                                            <span class="text-[10px] text-slate-400 uppercase font-semibold block">Harga</span>
                                            <span class="text-sm font-bold text-blue-600">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <a href="{{ route('products.show', $item) }}"
                                           class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition">
                                            <span>Detail</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
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
