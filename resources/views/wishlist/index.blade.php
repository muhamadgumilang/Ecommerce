<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wishlist - {{ config('app.name', 'E-Commerce') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 shadow-sm backdrop-blur-xl">
        <div class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-bold text-slate-900">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-600 text-sm font-bold text-white">E</span>
                E-Commerce
            </a>
            <nav class="flex items-center gap-2 text-sm">
                <a href="{{ route('catalog.index') }}" class="rounded-lg px-3 py-2 font-medium text-slate-600 hover:bg-slate-100">Katalog</a>
                <a href="{{ route('orders.index') }}" class="rounded-lg px-3 py-2 font-medium text-slate-600 hover:bg-slate-100">Pesanan Saya</a>
                <x-cart-link />
                <x-notification-menu />
                <x-wishlist-link />
                <x-profile-menu />
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="mb-6">
            <h1 class="text-3xl font-bold tracking-tight text-slate-900">Wishlist Saya</h1>
            <p class="mt-1 text-sm text-slate-500">Simpan produk yang ingin Anda beli nanti.</p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            @forelse ($wishlists as $wishlist)
                @php($product = $wishlist->product)
                @if ($product)
                    <article class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <a href="{{ route('products.show', $product) }}" class="flex h-48 items-center justify-center bg-slate-100">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-4xl text-slate-300">&#128722;</span>
                            @endif
                        </a>
                        <div class="flex flex-1 flex-col justify-between gap-4 p-5">
                            <div>
                                <p class="text-xs font-semibold text-blue-600">{{ $product->category?->name ?? 'Produk' }}</p>
                                <h2 class="mt-1 line-clamp-2 font-bold text-slate-900">{{ $product->product_name }}</h2>
                                <p class="mt-2 text-sm font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('products.show', $product) }}" class="flex-1 rounded-xl bg-blue-600 px-3 py-2 text-center text-xs font-semibold text-white hover:bg-blue-700">Detail</a>
                                <form action="{{ route('wishlist.destroy', $product) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-xl bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @endif
            @empty
                <div class="col-span-full rounded-2xl border border-slate-200 bg-white p-12 text-center">
                    <div class="text-5xl">♡</div>
                    <h2 class="mt-4 font-bold text-slate-900">Wishlist masih kosong</h2>
                    <p class="mt-1 text-sm text-slate-500">Simpan produk favorit Anda dari katalog.</p>
                    <a href="{{ route('catalog.index') }}" class="mt-5 inline-flex rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Jelajahi Katalog</a>
                </div>
            @endforelse
        </div>
    </main>
</body>

</html>
