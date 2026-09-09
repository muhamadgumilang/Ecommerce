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
    <body class="font-sans antialiased bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-blue-500 selection:text-white">

        <!-- Wrapper Utama -->
        <div class="flex flex-col min-h-screen justify-between">

            <!-- Header / Navbar -->
            <header class="bg-slate-900/90 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                    <!-- Logo / Judul Brand -->
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-600 text-white p-2.5 rounded-xl font-bold text-sm tracking-wide shadow-lg shadow-blue-600/30">
                            E-STORE
                        </div>
                        <span class="font-bold text-slate-200 text-base tracking-tight hidden sm:inline">Marketplace System</span>
                    </div>

                    <!-- Tombol Navigasi Kanan (Auth Check) -->
                    <nav class="flex items-center space-x-3">
                        @auth
                            @if(Auth::user()->role === 'Admin')
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-semibold text-xs uppercase tracking-wider transition shadow-md shadow-blue-600/20">
                                    Admin Dashboard
                                </a>
                            @else
                                <span class="text-sm font-medium text-slate-300">Halo, <strong class="text-white">{{ Auth::user()->name }}</strong></span>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-xs font-semibold text-rose-400 hover:text-rose-300 transition">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-300 hover:text-white transition">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-semibold text-xs uppercase tracking-wider transition shadow-md shadow-blue-600/20">
                                    Daftar
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </header>

            <!-- Konten Utama -->
            <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">

                <!-- Hero Section (Tampilan Baru yang Lebih Clean & Gelap) -->
                <div class="bg-slate-800/80 border border-slate-700/80 p-8 sm:p-12 rounded-3xl shadow-xl mb-14 text-center">
                    <div class="max-w-2xl mx-auto">
                        <!-- Badge Kecil -->
                        <div class="inline-flex items-center space-x-2 bg-blue-500/10 text-blue-400 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider mb-5 border border-blue-500/20">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                            <span>Katalog Produk Terbaru</span>
                        </div>

                        <!-- Judul Utama -->
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight mb-4 leading-tight">
                            Temukan Produk Terbaik & <span class="text-blue-400">Kelola Toko</span> dengan Mudah
                        </h1>

                        <!-- Deskripsi -->
                        <p class="text-sm sm:text-base text-slate-400 mb-8 leading-relaxed">
                            Sistem manajemen berbasis web yang cepat, responsif, dan nyaman digunakan untuk kebutuhan e-commerce Anda.
                        </p>

                        <!-- Tombol Aksi -->
                        <div class="flex flex-col sm:flex-row justify-center gap-3">
                            @auth
                                @if(Auth::user()->role === 'Admin')
                                    <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-600/20">
                                        Buka Panel Admin Dashboard
                                    </a>
                                @else
                                    <div class="p-3 bg-slate-900/60 rounded-xl text-slate-300 text-sm font-medium border border-slate-700">
                                        Anda masuk sebagai <strong class="text-white">{{ Auth::user()->name }}</strong>.
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl transition text-sm shadow-lg shadow-blue-600/20">
                                    Masuk / Login Sekarang
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold rounded-xl transition text-sm">
                                        Daftar Akun Baru
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>

                <!-- Section Daftar Produk -->
                <div>
                    <div class="flex justify-between items-center mb-6 border-b border-slate-800 pb-4">
                        <div>
                            <h2 class="text-xl font-bold text-white">Produk Pilihan Kami</h2>
                            <p class="text-xs text-slate-400 mt-0.5">Jelajahi produk berkualitas tinggi yang tersedia untuk Anda.</p>
                        </div>
                        <span class="text-xs bg-slate-800 text-slate-300 px-3 py-1 rounded-lg border border-slate-700 font-medium">
                            Total: {{ isset($products) ? $products->count() : 0 }} Produk
                        </span>
                    </div>

                    @if(isset($products) && $products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                <div class="bg-slate-800/60 rounded-2xl shadow-lg border border-slate-700/80 overflow-hidden hover:border-blue-500/50 transition flex flex-col justify-between group">
                                    <div>
                                        <!-- Gambar Produk dengan Ukuran Terkunci -->
                                        <div style="height: 180px;" class="bg-slate-900 flex items-center justify-center text-slate-600 font-medium overflow-hidden relative">
                                            @if(!empty($product->image))
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name ?? $product->name }}" style="width: 100%; height: 100%; object-fit: cover;" class="group-hover:scale-105 transition duration-300">
                                            @else
                                                <span class="text-xs text-slate-500">No Image Available</span>
                                            @endif

                                            @if(isset($product->category))
                                                <span class="absolute top-2.5 left-2.5 bg-slate-900/80 backdrop-blur-sm text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-md border border-slate-700">
                                                    {{ $product->category->name }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Informasi Produk -->
                                        <div class="p-4">
                                            <h3 class="font-bold text-white text-sm mb-1 truncate group-hover:text-blue-400 transition">
                                                {{ $product->product_name ?? $product->name }}
                                            </h3>
                                            <p class="text-xs text-slate-400 line-clamp-2 mb-3 leading-relaxed">
                                                {{ $product->description ?? 'Tidak ada deskripsi produk.' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="px-4 pb-4 flex items-center justify-between mt-auto">
                                        <span class="text-blue-400 font-extrabold text-sm">
                                            Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}
                                        </span>
                                        <a href="#" class="px-3 py-1.5 bg-blue-600/20 hover:bg-blue-600 text-blue-400 hover:text-white transition rounded-lg text-xs font-semibold border border-blue-500/30">
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Tampilan Jika Produk Kosong -->
                        <div class="text-center py-12 bg-slate-800/40 rounded-2xl border border-dashed border-slate-700">
                            <p class="text-slate-400 text-xs font-medium">Belum ada produk yang tersedia saat ini. Silakan tambahkan produk melalui panel admin.</p>
                        </div>
                    @endif
                </div>

            </main>

            <!-- Footer -->
            <footer class="bg-slate-900 border-t border-slate-800 py-6 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </footer>

        </div>
    </body>
</html>
