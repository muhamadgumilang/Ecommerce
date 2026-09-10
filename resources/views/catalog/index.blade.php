<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Katalog Produk</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles (Tailwind) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-between selection:bg-blue-500 selection:text-white">

        <div class="flex flex-col min-h-screen justify-between">

            <!-- Navbar -->
            <header class="bg-slate-900/90 backdrop-blur-md border-b border-slate-800 sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <a href="{{ url('/') }}" class="bg-blue-600 text-white p-2.5 rounded-xl font-bold text-sm tracking-wide shadow-lg shadow-blue-600/30">
                            E-STORE
                        </a>
                        <span class="font-bold text-slate-200 text-base tracking-tight hidden sm:inline">Katalog Produk</span>
                    </div>

                    <nav class="flex items-center space-x-3">
                        <a href="{{ url('/') }}" class="px-4 py-2 text-sm font-semibold text-slate-300 hover:text-white transition">
                            Beranda
                        </a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-semibold text-xs uppercase tracking-wider transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-semibold text-xs uppercase tracking-wider transition">
                                Masuk
                            </a>
                        @endauth
                    </nav>
                </div>
            </header>

            <!-- Konten Utama Katalog -->
            <main class="flex-1 py-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">

                <!-- Header Katalog -->
                <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-800 pb-6">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Semua Produk Tersedia</h1>
                        <p class="text-xs sm:text-sm text-slate-400 mt-1">Temukan pilihan produk terbaik dan berkualitas dari toko kami.</p>
                    </div>
                    <span class="text-xs bg-slate-800 text-slate-300 px-3.5 py-1.5 rounded-xl border border-slate-700 font-semibold">
                        Total: {{ $products->total() ?? $products->count() }} Produk
                    </span>
                </div>

                <!-- Grid Produk -->
                @if(isset($products) && $products->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($products as $product)
                            <div class="bg-slate-800/60 rounded-2xl shadow-lg border border-slate-700/80 overflow-hidden hover:border-blue-500/50 transition flex flex-col justify-between group">
                                <div>
                                    <!-- Gambar Produk Terkunci -->
                                    <div style="height: 190px;" class="bg-slate-900 flex items-center justify-center text-slate-600 font-medium overflow-hidden relative">
                                        @if(!empty($product->image))
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" style="width: 100%; height: 100%; object-fit: cover;" class="group-hover:scale-105 transition duration-300">
                                        @else
                                            <span class="text-xs text-slate-500">No Image Available</span>
                                        @endif

                                        @if(isset($product->category))
                                            <span class="absolute top-2.5 left-2.5 bg-slate-900/80 backdrop-blur-sm text-slate-300 text-[10px] font-bold px-2.5 py-1 rounded-md border border-slate-700">
                                                {{ $product->category->name }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Informasi -->
                                    <div class="p-4">
                                        <h3 class="font-bold text-white text-sm mb-1 truncate group-hover:text-blue-400 transition">
                                            {{ $product->product_name }}
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

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-slate-800/40 rounded-2xl border border-dashed border-slate-700">
                        <p class="text-slate-400 text-sm font-medium">Belum ada produk yang terdaftar di katalog saat ini.</p>
                    </div>
                @endif

            </main>

            <!-- Footer -->
            <footer class="bg-slate-900 border-t border-slate-800 py-6 text-center text-xs text-slate-500">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </footer>

        </div>
    </body>
</html>
