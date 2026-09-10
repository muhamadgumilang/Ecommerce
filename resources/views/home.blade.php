<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Store</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles (Tailwind) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white">

        <!-- Wrapper Utama -->
        <div class="flex flex-col min-h-screen justify-between">

            <!-- Header / Navbar (Nuansa Putih & Border Halus) -->
            <header class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                    <!-- Logo / Judul Brand -->
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-600 text-white p-2.5 rounded-xl font-bold text-sm tracking-wide shadow-md shadow-blue-600/20">
                            E-STORE
                        </div>
                        <span class="font-bold text-slate-800 text-base tracking-tight hidden sm:inline">Marketplace System</span>
                    </div>

                    <!-- Tombol Navigasi Kanan (Ditambah Menu Katalog) -->
                    <nav class="flex items-center space-x-4">
                        <!-- Tautan ke Katalog -->
                        <a href="{{ route('catalog.index') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
                            Katalog
                        </a>

                        @auth
                            @if(Auth::user()->role === 'Admin')
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs uppercase tracking-wider transition shadow-md shadow-blue-600/20">
                                    Admin Dashboard
                                </a>
                            @else
                                <span class="text-sm font-medium text-slate-600">Halo, <strong class="text-slate-900">{{ Auth::user()->name }}</strong></span>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-xs font-semibold text-rose-600 hover:text-rose-700 transition">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-blue-600 transition">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-xs uppercase tracking-wider transition shadow-md shadow-blue-600/20">
                                    Daftar
                                 </a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </header>

            <!-- Konten Utama -->
            <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">

                <!-- Hero Section (Nuansa Putih Bersih dengan Aksen Biru) -->
                <div class="bg-white border border-slate-200/80 p-8 sm:p-12 rounded-3xl shadow-xl shadow-slate-200/50 mb-14 text-center">
                    <div class="max-w-2xl mx-auto">
                        <!-- Badge Kecil -->
                        <div class="inline-flex items-center space-x-2 bg-blue-50 text-blue-600 text-xs font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-5 border border-blue-100">
                            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                            <span>Katalog Produk Terbaru</span>
                        </div>

                        <!-- Judul Utama -->
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-4 leading-tight">
                            Temukan Produk Terbaik & <span class="text-blue-600">Kelola Toko</span> dengan Mudah
                        </h1>

                        <!-- Deskripsi -->
                        <p class="text-sm sm:text-base text-slate-600 mb-8 leading-relaxed">
                            Sistem manajemen berbasis web yang cepat, responsif, dan nyaman digunakan untuk kebutuhan e-commerce Anda.
                        </p>

                        <!-- Tombol Aksi -->
                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            <a href="{{ route('catalog.index') }}" class="px-6 py-3 bg-white hover:bg-slate-50 text-blue-600 border border-blue-200 font-bold rounded-xl transition text-sm shadow-sm">
                                Lihat Katalog Lengkap
                            </a>

                            @auth
                                @if(Auth::user()->role === 'Admin')
                                    <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-600/20">
                                        Buka Panel Admin Dashboard
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-600/20">
                                    Masuk / Login Sekarang
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Section Daftar Produk -->
                <div>
                    <div class="flex justify-between items-center mb-6 border-b border-slate-200 pb-4">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900">Produk Pilihan Kami</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Jelajahi produk berkualitas tinggi yang tersedia untuk Anda.</p>
                        </div>
                        <a href="{{ route('catalog.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold transition">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    @if(isset($products) && $products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                <div class="bg-white rounded-2xl shadow-md border border-slate-200/80 overflow-hidden hover:border-blue-300 hover:shadow-lg transition flex flex-col justify-between group">
                                    <div>
                                        <!-- Gambar Produk dengan Ukuran Terkunci -->
                                        <div style="height: 180px;" class="bg-slate-100 flex items-center justify-center text-slate-400 font-medium overflow-hidden relative">
                                            @if(!empty($product->image))
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name ?? $product->name }}" style="width: 100%; height: 100%; object-fit: cover;" class="group-hover:scale-105 transition duration-300">
                                            @else
                                                <span class="text-xs text-slate-400">No Image Available</span>
                                            @endif

                                            @if(isset($product->category))
                                                <span class="absolute top-2.5 left-2.5 bg-white/90 backdrop-blur-sm text-slate-700 text-[10px] font-bold px-2.5 py-1 rounded-md border border-slate-200 shadow-sm">
                                                    {{ $product->category->name }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Informasi Produk -->
                                        <div class="p-4">
                                            <h3 class="font-bold text-slate-900 text-sm mb-1 truncate group-hover:text-blue-600 transition">
                                                {{ $product->product_name ?? $product->name }}
                                            </h3>
                                            <p class="text-xs text-slate-500 line-clamp-2 mb-3 leading-relaxed">
                                                {{ $product->description ?? 'Tidak ada deskripsi produk.' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="px-4 pb-4 flex items-center justify-between mt-auto">
                                        <span class="text-blue-600 font-extrabold text-sm">
                                            Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                                        </span>
                                        <a href="#" class="px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white transition rounded-lg text-xs font-semibold border border-blue-100">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Tampilan Jika Produk Kosong -->
                        <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300 shadow-sm">
                            <p class="text-slate-500 text-xs font-medium">Belum ada produk yang tersedia saat ini. Silakan tambahkan produk melalui panel admin.</p>
                        </div>
                    @endif
                </div>

            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </footer>

        </div>
    </body>
</html>
