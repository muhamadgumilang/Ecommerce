<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->product_name }} - {{ config('app.name', 'E-Commerce') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[72px] flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="font-bold text-lg text-slate-900 flex items-center gap-2 shrink-0">
                <span
                    class="bg-blue-600 text-white w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm shadow-blue-600/25">E</span>
                <span class="tracking-tight">E-Commerce</span>
            </a>

            <nav class="flex items-center gap-1 sm:gap-2 text-sm">
                <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                    Beranda
                </a>
                <a href="{{ route('catalog.index') }}"
                    class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                    Katalog
                </a>
                <a href="{{ route('orders.index') }}"
                    class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                    Pesanan Saya
                </a>
                <a href="{{ route('cart.index') }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-lg leading-none text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition" title="Keranjang"
                    aria-label="Keranjang">
                    &#128722;
                </a>

                @auth
                    @if (Auth::user()->role === 'Admin')
                        <a href="{{ url('/dashboard') }}"
                            class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm shadow-blue-600/20">
                            Admin
                        </a>
                    @else
                        <span class="text-xs text-slate-500 hidden lg:inline px-2">Halo, <strong
                                class="text-slate-800">{{ Auth::user()->name }}</strong></span>
                    @endif
                    <a href="{{ route('profile.edit') }}"
                        class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                        Masuk
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition">
                            Daftar
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-8 sm:py-10 flex-1">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs -->
            <div class="flex items-center justify-between">
                <nav class="flex items-center text-sm text-slate-500 space-x-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('catalog.index') }}" class="hover:text-blue-600 transition">Katalog</a>
                    <span>&rsaquo;</span>
                    <span
                        class="text-slate-900 font-medium truncate max-w-xs sm:max-w-md">{{ $product->product_name }}</span>
                </nav>

                <a href="{{ route('catalog.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Katalog</span>
                </a>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div
                    class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Product Card: 2 Column Layout -->
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-start">

                    <!-- Left: Product Image -->
                    <div class="space-y-4">
                        <div
                            class="w-full aspect-square rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center relative group shadow-inner">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->product_name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="text-center p-8 text-slate-400">
                                    <span class="text-5xl block mb-2">&#128722;</span>
                                    <span class="text-sm font-medium">Gambar Produk Belum Tersedia</span>
                                </div>
                            @endif

                            @if ($product->category)
                                <span
                                    class="absolute top-4 left-4 bg-white/90 backdrop-blur-md text-blue-600 font-bold text-xs px-3 py-1.5 rounded-full shadow-sm border border-slate-100">
                                    {{ $product->category->category_name }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Right: Product Information & Purchase Controls -->
                    <div class="flex flex-col justify-between space-y-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Produk Tersedia
                                </span>
                                <span class="text-xs text-slate-400">&bull;</span>
                                <span class="text-xs text-slate-500">ID Produk: #{{ $product->product_id }}</span>
                            </div>

                            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight leading-tight">
                                {{ $product->product_name }}
                            </h1>

                            <div class="mt-4 pb-5 border-b border-slate-100">
                                <span
                                    class="text-xs text-slate-400 uppercase tracking-wider font-semibold block mb-1">Harga
                                    Produk</span>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-3xl font-extrabold text-blue-600">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-5 space-y-2">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Deskripsi Produk
                                </h3>
                                <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line">
                                    {{ $product->description ?: 'Tidak ada deskripsi detail untuk produk ini.' }}
                                </p>
                            </div>

                            <!-- Stock Information -->
                            <div
                                class="mt-5 p-3.5 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-medium">Ketersediaan Stok:</span>
                                @if ($product->stock > 5)
                                    <span
                                        class="font-bold text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                                        {{ $product->stock }} unit tersedia
                                    </span>
                                @elseif ($product->stock > 0)
                                    <span
                                        class="font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
                                        Tersisa {{ $product->stock }} unit lagi!
                                    </span>
                                @else
                                    <span
                                        class="font-bold text-rose-600 bg-rose-50 px-2.5 py-1 rounded-lg border border-rose-200">
                                        Stok Habis
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Purchase Action Section -->
                        <div class="border-t border-slate-100 pt-6">
                            @if ($product->stock > 0)
                                @auth
                                    <form method="POST" action="{{ route('cart.add') }}" class="space-y-4">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">

                                        <div>
                                            <label for="quantity"
                                                class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-2">
                                                Tentukan Jumlah
                                            </label>
                                            <div class="flex items-center gap-3">
                                                <input id="quantity" name="quantity" type="number" min="1"
                                                    max="{{ $product->stock }}" value="1"
                                                    class="w-24 rounded-xl border-slate-300 text-sm font-semibold text-center focus:border-blue-500 focus:ring-blue-500 p-2.5">
                                                <span class="text-xs text-slate-400">Maks. {{ $product->stock }}
                                                    unit</span>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
                                            <button type="submit"
                                                class="inline-flex items-center justify-center gap-2 rounded-xl border-2 border-blue-600 bg-blue-50/50 px-5 py-3 text-sm font-semibold text-blue-600 hover:bg-blue-600 hover:text-white transition shadow-sm">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z">
                                                    </path>
                                                </svg>
                                                <span>Tambah ke Keranjang</span>
                                            </button>

                                            <button type="submit" formaction="{{ route('cart.buy-now') }}"
                                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 active:scale-[0.99] transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                <span>Beli Sekarang</span>
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 text-center space-y-3">
                                        <p class="text-sm text-blue-900 font-medium">Silakan masuk ke akun Anda untuk
                                            memesan produk ini.</p>
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('login') }}"
                                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-md shadow-blue-600/20 transition">
                                                Masuk Sekarang
                                            </a>
                                            @if (Route::has('register'))
                                                <a href="{{ route('register') }}"
                                                    class="px-5 py-2.5 bg-white text-slate-700 hover:bg-slate-100 text-xs font-semibold rounded-xl border border-slate-200 transition">
                                                    Daftar Akun Baru
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endauth
                            @else
                                <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl text-center">
                                    <p class="text-sm font-semibold text-rose-700">Mohon maaf, stok produk ini saat ini
                                        sedang habis.</p>
                                    <p class="text-xs text-rose-500 mt-1">Silakan cek kembali di lain waktu atau cari
                                        produk serupa di katalog kami.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Assurance Icons -->
                        <div
                            class="grid grid-cols-3 gap-2 pt-4 border-t border-slate-100 text-center text-slate-500 text-[11px]">
                            <div class="p-2">
                                <span class="text-lg block mb-1">&#128737;</span>
                                <span class="font-medium text-slate-700 block">100% Asli</span>
                                <span>Kualitas Terjamin</span>
                            </div>
                            <div class="p-2">
                                <span class="text-lg block mb-1">&#128666;</span>
                                <span class="font-medium text-slate-700 block">Pengiriman</span>
                                <span>Aman & Cepat</span>
                            </div>
                            <div class="p-2">
                                <span class="text-lg block mb-1">&#128222;</span>
                                <span class="font-medium text-slate-700 block">Bantuan</span>
                                <span>Customer Service</span>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} E-Commerce. All rights reserved.
    </footer>

</body>

</html>
