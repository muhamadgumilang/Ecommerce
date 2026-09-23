<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Katalog Produk - {{ config('app.name', 'E-Commerce') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased selection:bg-blue-600 selection:text-white">

    <!-- Header / Navbar -->
    <header
        class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/80 shadow-[0_10px_30px_rgba(15,23,42,0.04)] backdrop-blur-xl">
        <div class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2 text-lg font-bold text-slate-900">
                <span
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-sky-500 text-sm font-bold text-white shadow-lg shadow-blue-600/25">E</span>
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
                <x-cart-link />
                <x-notification-menu />
                <x-wishlist-link />

                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm shadow-blue-600/20">
                            Admin
                        </a>
                    @elseif (Auth::user()->isSeller())
                        <a href="{{ route('seller.dashboard') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700 transition shadow-sm shadow-emerald-600/20">
                            <span>🏪</span> Toko Saya
                        </a>
                    @else
                        <span class="text-xs text-slate-500 hidden lg:inline px-2">Halo, <strong
                                class="text-slate-800">{{ Auth::user()->name }}</strong></span>
                    @endif
                    <x-profile-menu />
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
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs -->
            <nav class="flex items-center text-sm text-slate-500 space-x-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                <span>&rsaquo;</span>
                <span class="text-slate-900 font-medium">Katalog Produk</span>
            </nav>

            <!-- Header Section -->
            <div
                class="soft-card flex flex-col items-start justify-between gap-4 rounded-2xl p-6 sm:flex-row sm:items-center">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Katalog Produk</h1>
                    <p class="mt-1 text-sm text-slate-500">Temukan berbagai koleksi produk berkualitas terbaik dengan
                        harga terjangkau.</p>
                </div>
                <form action="{{ route('catalog.index') }}" method="GET" class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                    <label for="catalog-search" class="sr-only">Cari produk atau kategori</label>
                    <input id="catalog-search" type="search" name="search" value="{{ $search }}"
                        placeholder="Cari produk atau kategori..."
                        class="w-full rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:w-72">
                    <button type="submit"
                        class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                        Cari
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[250px_minmax(0,1fr)]">
                <aside class="soft-card h-fit rounded-2xl p-5 lg:sticky lg:top-24">
                    <div class="flex items-center gap-2">
                        <span class="text-xl text-blue-600">☷</span>
                        <h2 class="text-base font-bold text-slate-900">Filter Produk</h2>
                    </div>

                    <form action="{{ route('catalog.index') }}" method="GET" class="mt-6">
                        <input type="hidden" name="search" value="{{ $search }}">
                        <label for="category" class="mb-2 block text-xs font-semibold uppercase tracking-wide text-slate-500">Kategori</label>
                        <select id="category" name="category" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->ids }}" {{ $categoryFilter === $category->ids ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        <label class="mt-5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Rentang Harga</label>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ $minPrice }}" min="0" placeholder="Min"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <input type="number" name="max_price" value="{{ $maxPrice }}" min="0" placeholder="Max"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 px-3 py-2.5 text-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>

                        <button type="submit" class="mt-5 w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700">
                            Terapkan Filter
                        </button>
                    </form>

                    @if ($categoryFilter !== '' || $minPrice !== null || $maxPrice !== null)
                        <a href="{{ route('catalog.index', ['search' => $search]) }}" class="mt-3 block text-center text-xs font-semibold text-blue-600 hover:text-blue-700">
                            Hapus filter
                        </a>
                    @endif
                </aside>

                <section class="min-w-0">
                    <div class="mb-4 flex items-center justify-between text-xs text-slate-500">
                        <span>Total: <strong class="text-blue-600">{{ $products->total() }} Produk</strong></span>
                        @if ($search !== '')
                            <a href="{{ route('catalog.index') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                Hapus pencarian
                            </a>
                        @endif
                    </div>

            <!-- Grid Produk -->
            @if ($products->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-16 text-center max-w-xl mx-auto">
                    <div class="text-5xl mb-4">&#128230;</div>
                    <h2 class="text-lg font-bold text-slate-900">
                        {{ $search !== '' ? 'Produk Tidak Ditemukan' : 'Belum Ada Produk Tersedia' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">
                        {{ $search !== '' ? 'Coba gunakan kata kunci produk atau kategori lainnya.' : 'Produk saat ini sedang dalam persiapan. Silakan periksa kembali nanti.' }}
                    </p>
                    <a href="{{ $search !== '' ? route('catalog.index') : route('home') }}"
                        class="inline-block mt-6 px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition">
                        {{ $search !== '' ? 'Lihat Semua Produk' : 'Kembali ke Beranda' }}
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($products as $item)
                        <div
                            class="soft-card group flex flex-col overflow-hidden rounded-2xl transition duration-300 hover:-translate-y-1 hover:shadow-[0_18px_35px_rgba(37,99,235,0.10)]">
                            <!-- Gambar Produk -->
                            <div class="h-52 bg-slate-100 relative overflow-hidden flex items-center justify-center">
                                @if ($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->product_name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <span class="text-3xl text-slate-300">&#128722;</span>
                                @endif

                                @if ($item->category)
                                    <span
                                        class="absolute top-3 left-3 bg-white/90 backdrop-blur-md text-blue-600 text-[11px] font-bold px-2.5 py-1 rounded-lg shadow-sm border border-slate-100">
                                        {{ $item->category->category_name }}
                                    </span>
                                @endif

                            </div>

                            <!-- Detail Produk -->
                            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                                <div>
                                    <h3
                                        class="font-bold text-slate-900 text-base leading-snug line-clamp-1 group-hover:text-blue-600 transition">
                                        <a href="{{ route('products.show', $item) }}">
                                            {{ $item->product_name }}
                                        </a>
                                    </h3>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500 mt-1">
                                        <span>🏪</span>
                                        <span
                                            class="font-medium text-slate-600 truncate">{{ $item->seller?->name ?? 'E-Commerce' }}</span>
                                    </div>
                                    <p class="text-slate-500 text-xs mt-1.5 line-clamp-2 leading-relaxed">
                                        {{ $item->description ?? 'Tidak ada deskripsi produk.' }}
                                    </p>
                                </div>

                                <div>
                                    <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
                                        <span>Stok:</span>
                                        @if ($item->stock > 0)
                                            <span class="text-emerald-600 font-semibold">{{ $item->stock }}
                                                unit</span>
                                        @else
                                            <span class="text-rose-500 font-semibold">Habis</span>
                                        @endif
                                    </div>

                                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                                        <div>
                                            <span
                                                class="text-[10px] text-slate-400 uppercase font-semibold block">Harga</span>
                                            <span class="text-sm font-bold text-blue-600">
                                                Rp {{ number_format($item->price, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        <a href="{{ route('products.show', $item) }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-xl shadow-sm shadow-blue-500/20 transition">
                                            <span>Detail</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                        @auth
                                            @if (Auth::user()->isCustomer())
                                                <form action="{{ route('wishlist.store', $item) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" title="Simpan ke wishlist"
                                                        class="inline-flex items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-lg leading-none text-rose-600 hover:bg-rose-100">♡</button>
                                                </form>
                                            @endif
                                        @endauth
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
                </section>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} E-Commerce. All rights reserved.
    </footer>

</body>

</html>
