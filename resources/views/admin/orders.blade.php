<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Admin</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="p-6">
    <main class="max-w-6xl mx-auto">
        <h1 class="text-2xl font-bold mb-4">Pesanan</h1>
        @foreach ($orders as $order)
            <div class="border-b py-3">#{{ $order->order_id }} - {{ $order->order_status }} - Rp
                {{ number_format($order->total_amount, 0, ',', '.') }}</div>
        @endforeach
    </main>
</body>

</html>
