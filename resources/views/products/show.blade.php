<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $product->product_name }}</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 p-6">
    <main class="max-w-3xl mx-auto bg-white rounded-lg p-6 shadow">
        <h1 class="text-3xl font-bold">{{ $product->product_name }}</h1>
        <p class="mt-4">{{ $product->description }}</p>
        <p class="mt-4 text-xl font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
        <p class="mt-2">Stok: {{ $product->stock }}</p><a class="text-blue-600"
            href="{{ url('/products') }}">Kembali</a>
    </main>
</body>

</html>
