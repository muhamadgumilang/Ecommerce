<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 p-6">
    <main class="max-w-xl mx-auto bg-white rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-4">Pembayaran Pesanan #{{ $order->order_id }}</h1>
        <form method="POST" action="{{ route('payments.store', $order) }}">@csrf<select name="payment_method" required
                class="w-full rounded border-slate-300">
                <option value="Bank Transfer">Bank Transfer</option>
                <option value="E-Wallet">E-Wallet</option>
                <option value="COD">COD</option>
            </select><button class="mt-4 rounded bg-blue-600 px-4 py-2 text-white">Kirim pembayaran</button></form>
    </main>
</body>

</html>
