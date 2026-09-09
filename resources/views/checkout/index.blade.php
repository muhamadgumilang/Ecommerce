<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Checkout</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 p-6">
    <main class="max-w-xl mx-auto bg-white rounded-lg p-6 shadow">
        <h1 class="text-2xl font-bold mb-4">Checkout</h1>
        <p class="mb-4">Subtotal: Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
        <form method="POST" action="{{ route('checkout.process') }}" class="space-y-4">@csrf<input
                name="shipping_address" required placeholder="Alamat pengiriman"
                class="w-full rounded border-slate-300"><input name="courier" required placeholder="Kurir"
                class="w-full rounded border-slate-300"><input name="shipping_fee" type="number" min="0"
                required placeholder="Biaya kirim" class="w-full rounded border-slate-300">
            <textarea name="notes" placeholder="Catatan" class="w-full rounded border-slate-300"></textarea><button class="rounded bg-blue-600 px-4 py-2 text-white">Proses checkout</button>
        </form>
    </main>
</body>

</html>
