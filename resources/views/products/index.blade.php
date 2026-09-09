<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Produk</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-800 p-6">
    <main class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Katalog Produk</h1>
        <form method="GET" class="mb-6"><select name="category_id" onchange="this.form.submit()"
                class="rounded border-slate-300">
                <option value="">Semua kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->category_id }}" @selected(request('category_id') == $category->category_id)>{{ $category->name }}
                    </option>
                @endforeach
            </select>
        </form>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($products as $product)
                <article class="bg-white rounded-lg p-5 shadow">
                    <h2 class="font-semibold text-lg">{{ $product->product_name }}</h2>
                    <p class="text-sm text-slate-500">{{ $product->category->name ?? 'Tanpa kategori' }}</p>
                    <p class="mt-3 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p><a
                        class="text-blue-600" href="{{ url('/products/' . $product->product_id) }}">Lihat detail</a>
            </article>@empty<p>Belum ada produk.</p>
            @endforelse
        </div>
    </main>
</body>

</html>
