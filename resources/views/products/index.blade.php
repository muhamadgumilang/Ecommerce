<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog Produk - {{ config('app.name', 'E-Commerce') }}</title>

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
                <a href="{{ route('home') }}"
                    class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                    Beranda
                </a>
                <a href="{{ route('catalog.index') }}"
                    class="inline-flex items-center px-3 py-2 rounded-lg font-semibold text-blue-600 bg-blue-50">
                    Katalog
                </a>
                <a href="{{ route('orders.index') }}"
                    class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                    Pesanan Saya
                </a>
                <a href="{{ route('cart.index') }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-lg leading-none text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition"
                    title="Keranjang" aria-label="Keranjang">
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
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                        Masuk
                    </a>
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

            <!-- Filter Section -->
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Katalog Produk</h1>
                    <p class="text-sm text-slate-500 mt-1">Jelajahi produk pilihan kami berdasarkan kategori.</p>
                </div>
                <form method="GET" class="w-full sm:w-auto">
                    <select name="category_id" onchange="this.form.submit()"
                        class="rounded-xl border-slate-200 text-sm focus:border-blue-500 focus:ring-blue-500 py-2 pl-3 pr-8 shadow-sm">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->category_id }}" @selected(request('category_id') == $category->category_id)>
                                {{ $category->category_name ?? $category->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Products Grid -->
            <div class="grid gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @forelse($products as $product)
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md hover:border-blue-300 transition duration-200 flex flex-col group">
                        <div class="h-48 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    alt="{{ $product->product_name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <span class="text-3xl text-slate-300">&#128722;</span>
                            @endif
                            @if ($product->category)
                                <span
                                    class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-blue-600 text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-sm border border-slate-100">
                                    {{ $product->category->category_name ?? $product->category->name }}
                                </span>
                            @endif
                        </div>

                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <h3
                                    class="font-bold text-slate-900 text-base leading-snug line-clamp-1 group-hover:text-blue-600 transition">
                                    <a href="{{ route('products.show', $product) }}">
                                        {{ $product->product_name }}
                                    </a>
                                </h3>
                                <p class="text-slate-500 text-xs mt-1.5 line-clamp-2">
                                    {{ $product->description ?? 'Tidak ada deskripsi.' }}
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                <span class="text-sm font-bold text-blue-600">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                                <a href="{{ route('products.show', $product) }}"
                                    class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-16 text-center shadow-sm">
                        <div class="text-5xl mb-3">&#128230;</div>
                        <p class="text-base font-semibold text-slate-700">Belum ada produk untuk kategori ini.</p>
                        <a href="{{ route('catalog.index') }}"
                            class="inline-block mt-4 text-sm font-medium text-blue-600 hover:underline">
                            Lihat semua produk
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} E-Commerce. All rights reserved.
    </footer>

</body>

</html>
