<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->product_name }}</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 p-6">
    <main class="max-w-3xl mx-auto">
        <a class="text-sm text-blue-600 hover:underline" href="{{ route('catalog.index') }}">&larr; Kembali ke katalog</a>

        <div class="mt-4 overflow-hidden rounded-2xl bg-white shadow-sm">
            <div class="h-72 bg-slate-100 flex items-center justify-center">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}"
                        class="h-full w-full object-cover">
                @else
                    <span class="text-sm text-slate-400">No Image Available</span>
                @endif
            </div>

            <div class="p-6">
                @if ($product->category)
                    <span class="text-xs font-semibold text-blue-600">{{ $product->category->name }}</span>
                @endif
                <h1 class="mt-2 text-3xl font-bold text-slate-900">{{ $product->product_name }}</h1>
                <p class="mt-4 text-slate-600">{{ $product->description ?? 'Tidak ada deskripsi produk.' }}</p>
                <p class="mt-6 text-2xl font-bold text-blue-600">Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
                <p class="mt-2 text-sm text-slate-500">Stok tersedia: {{ $product->stock }}</p>

                @if ($product->stock > 0)
                    @auth
                        <form method="POST" action="{{ route('cart.add') }}"
                            class="mt-6 flex flex-wrap items-center gap-3">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                            <label for="quantity" class="text-sm font-medium text-slate-700">Jumlah</label>
                            <input id="quantity" name="quantity" type="number" min="1" max="{{ $product->stock }}"
                                value="1" class="w-20 rounded-lg border-slate-300 text-sm">
                            <button type="submit"
                                class="rounded-lg border border-blue-200 bg-blue-50 px-5 py-2.5 text-xl text-blue-700 hover:bg-blue-100"
                                title="Tambah ke keranjang" aria-label="Tambah ke keranjang">
                                &#128722;
                            </button>
                            <button type="submit" formaction="{{ route('cart.buy-now') }}"
                                class="rounded-lg bg-blue-600 px-5 py-2.5 text-xl text-white hover:bg-blue-700"
                                title="Pesan sekarang" aria-label="Pesan sekarang">
                                &#9889;
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="mt-6 inline-block rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                            Masuk untuk Memesan
                        </a>
                    @endauth
                @else
                    <p class="mt-6 font-semibold text-red-600">Stok sedang habis.</p>
                @endif
            </div>
        </div>
    </main>
</body>

</html>
