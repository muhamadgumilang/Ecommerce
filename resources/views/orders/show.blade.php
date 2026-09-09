<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pesanan</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 p-6">
    <main class="max-w-3xl mx-auto bg-white rounded-lg p-6">
        <h1 class="text-2xl font-bold">Pesanan #{{ $order->order_id }}</h1>
        <p>Status: {{ $order->order_status }}</p>
        <div class="mt-4">
            @foreach ($order->orderDetails as $detail)
                <p>{{ $detail->product->product_name ?? 'Produk' }} x {{ $detail->quantity }}</p>
            @endforeach
        </div><a class="text-blue-600" href="{{ route('orders.index') }}">Kembali</a>
    </main>
</body>

</html>
