<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'E-Commerce') }} - Store</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles (Tailwind) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased bg-[#f5f7fb] text-slate-800 min-h-screen flex flex-col justify-between overflow-x-hidden selection:bg-blue-600 selection:text-white">

    <!-- Wrapper Utama -->
    <div class="flex flex-col min-h-screen justify-between">

        <!-- Header / Navbar (Nuansa Putih & Border Halus) -->
        <header class="bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[72px] flex items-center justify-between gap-4">
                <!-- Logo / Judul Brand -->
                <a href="{{ route('home') }}" class="font-bold text-lg text-slate-900 flex items-center gap-2 shrink-0">
                    <span
                        class="bg-blue-600 text-white w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm shadow-blue-600/25">E</span>
                    <span class="tracking-tight">E-Commerce</span>
                </a>

                <!-- Tombol Navigasi Kanan (Ditambah Menu Katalog & Pesanan) -->
                <nav class="flex items-center gap-1 sm:gap-2 text-sm">
                    <a href="{{ route('home') }}"
                        class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-semibold text-blue-600 bg-blue-50">
                        Beranda
                    </a>
                    <a href="{{ route('catalog.index') }}"
                        class="inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                        Katalog
                    </a>
                    @auth
                        <a href="{{ route('orders.index') }}"
                            class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                            Pesanan Saya
                        </a>
                        <a href="{{ route('cart.index') }}"
                            class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-lg leading-none text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition"
                            title="Keranjang" aria-label="Keranjang">
                            &#128722;
                        </a>
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
                            <button type="submit"
                                class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition">
                                Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-4 py-2.5 bg-blue-600 text-white text-xs font-semibold rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-600/20">
                                Daftar
                            </a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <!-- Konten Utama -->
        <main class="flex-1 py-8 sm:py-12 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">

            <!-- Hero Section (Nuansa Putih Bersih dengan Aksen Biru) -->
            <div
                class="relative overflow-hidden bg-slate-900 border border-slate-800 px-6 py-10 sm:px-12 sm:py-14 rounded-[2rem] shadow-2xl shadow-slate-300/40 mb-12">
                <div class="absolute -right-16 -top-20 w-64 h-64 rounded-full bg-blue-500/20 blur-3xl"></div>
                <div class="relative grid md:grid-cols-[1.15fr_.85fr] gap-8 lg:gap-12 items-center">
                    <div>
                        <!-- Badge Kecil -->
                        <div
                            class="inline-flex items-center gap-2 bg-white/10 text-blue-200 text-xs font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-5 border border-white/10">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                            <span>Belanja lebih mudah di E-Commerce</span>
                        </div>

                        <!-- Judul Utama -->
                        <h1 class="text-3xl sm:text-5xl font-extrabold text-white tracking-tight mb-4 leading-[1.1]">
                            Temukan barang yang tepat untuk kebutuhan Anda.
                        </h1>

                        <!-- Deskripsi -->
                        <p class="text-sm sm:text-base text-slate-300 max-w-xl mb-8 leading-relaxed">
                            Pilih produk berkualitas dari toko kami, lihat detailnya, lalu selesaikan pesanan dengan
                            proses
                            yang sederhana.
                        </p>

                        <!-- Tombol Aksi -->
                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            <a href="{{ route('catalog.index') }}"
                                class="px-6 py-3 bg-blue-500 hover:bg-blue-400 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-900/30">
                                Lihat Katalog Lengkap
                            </a>

                            @auth
                                @if (Auth::user()->role === 'Admin')
                                    <a href="{{ url('/dashboard') }}"
                                        class="px-6 py-3 bg-white/10 hover:bg-white/15 text-white border border-white/15 font-bold rounded-xl transition text-sm">
                                        Buka Panel Admin
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-600/20">
                                    Masuk / Login Sekarang
                                </a>
                            @endauth
                        </div>
                    </div>

                    @if (isset($products) && $products->count() > 0)
                        @php($featuredProduct = $products->first())
                        <div class="hidden md:block">
                            <div
                                class="relative max-w-sm ml-auto rounded-3xl bg-white/10 border border-white/15 p-3 shadow-2xl shadow-black/20 rotate-2 hover:rotate-0 transition duration-500">
                                <div class="aspect-[4/3] rounded-2xl overflow-hidden bg-slate-800">
                                    @if (!empty($featuredProduct->image))
                                        <img src="{{ asset('storage/' . $featuredProduct->image) }}"
                                            alt="{{ $featuredProduct->product_name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <div
                                            class="w-full h-full flex items-center justify-center text-blue-200 text-sm">
                                            Belum ada foto produk</div>
                                    @endif
                                </div>
                                <div class="px-2 pt-4 pb-1 flex items-end justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-[10px] uppercase tracking-wider font-semibold text-blue-200">
                                            Produk unggulan</p>
                                        <p class="mt-1 text-base font-bold text-white truncate">
                                            {{ $featuredProduct->product_name }}</p>
                                    </div>
                                    <span class="shrink-0 text-sm font-bold text-blue-200">Rp
                                        {{ number_format($featuredProduct->price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section Daftar Produk -->
            <div>
                <div
                    class="flex flex-col sm:flex-row sm:justify-between sm:items-end gap-3 mb-6 border-b border-slate-200 pb-4">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Produk Pilihan Kami</h2>
                        <p class="text-sm text-slate-500 mt-1">Pilihan terbaru yang siap Anda pesan hari ini.</p>
                    </div>
                    <a href="{{ route('catalog.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-semibold transition">
                        Lihat semua produk &rarr;
                    </a>
                </div>

                @if (isset($products) && $products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach ($products as $product)
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:-translate-y-1 hover:border-blue-300 hover:shadow-xl transition duration-300 flex flex-col justify-between group min-h-[390px]">
                                <div>
                                    <!-- Gambar Produk dengan Ukuran Terkunci -->
                                    <div
                                        class="aspect-[4/3] bg-slate-100 flex items-center justify-center text-slate-400 font-medium overflow-hidden relative">
                                        @if (!empty($product->image))
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->product_name ?? $product->name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                        @else
                                            <span class="text-xs text-slate-400">Belum ada foto</span>
                                        @endif

                                        @if (isset($product->category))
                                            <span
                                                class="absolute top-3 left-3 bg-white/95 backdrop-blur-sm text-slate-700 text-[10px] font-bold px-2.5 py-1 rounded-lg border border-slate-200 shadow-sm">
                                                {{ $product->category->name ?? $product->category->category_name }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Informasi Produk -->
                                    <div class="p-5">
                                        <h3
                                            class="font-bold text-slate-900 text-base mb-1 truncate group-hover:text-blue-600 transition">
                                            {{ $product->product_name ?? $product->name }}
                                        </h3>
                                        <p class="text-sm text-slate-500 line-clamp-2 mb-4 leading-relaxed">
                                            {{ $product->description ?? 'Tidak ada deskripsi produk.' }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="px-5 pb-5 flex items-center justify-between gap-3 mt-auto border-t border-slate-100 pt-4">
                                    <div>
                                        <span
                                            class="block text-[10px] text-slate-400 uppercase font-semibold tracking-wide">Harga</span>
                                        <span class="text-blue-600 font-extrabold text-base">
                                            Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <a href="{{ route('products.show', $product) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white transition rounded-xl text-xs font-semibold shadow-sm shadow-blue-600/20"
                                        title="Lihat detail produk" aria-label="Lihat detail produk">
                                        Detail <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <!-- Tampilan Jika Produk Kosong -->
                    <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300 shadow-sm">
                        <div class="text-4xl mb-3">&#128230;</div>
                        <h3 class="text-lg font-bold text-slate-900">Belum ada produk tersedia</h3>
                        <p class="text-sm text-slate-500 mt-1">Produk baru akan segera hadir di toko kami.</p>
                    </div>
                @endif
            </div>

        </main>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-400 mt-8">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <p class="text-white font-bold tracking-tight">E-Commerce</p>
                    <p class="text-xs mt-1">Temukan produk yang tepat untuk kebutuhan Anda.</p>
                </div>
                <div class="text-xs sm:text-right">
                    <p>&copy; {{ date('Y') }} E-Commerce</p>
                    <p class="mt-1 text-slate-500">Belanja nyaman, proses sederhana.</p>
                </div>
            </div>
        </footer>

    </div>
</body>

</html>
