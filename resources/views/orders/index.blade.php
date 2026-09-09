<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 p-6">
    <main class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Pesanan Saya</h1>
        @forelse($orders as $order)
            <div class="bg-white rounded p-4 mb-3"><a class="text-blue-600"
                    href="{{ route('orders.show', $order) }}">Pesanan #{{ $order->order_id }}</a>
                <p>Status: {{ $order->order_status }}</p>
                <p>Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
        </div>@empty<p>Belum ada pesanan.</p>
        @endforelse
    </main>
</body>

</html>
