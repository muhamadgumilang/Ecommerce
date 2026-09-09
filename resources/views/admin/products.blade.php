<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produk Admin</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="p-6">
    <main class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Produk</h1>
        @foreach ($products as $product)
            <div class="border-b py-3">{{ $product->product_name }} - Rp
                {{ number_format($product->price, 0, ',', '.') }}</div>
        @endforeach
    </main>
</body>

</html>
