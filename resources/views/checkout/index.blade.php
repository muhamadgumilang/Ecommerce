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
        <div class="mb-6 space-y-2 text-sm">
            <div class="flex justify-between">
                <span>Subtotal</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-slate-500">
                <span>Pengiriman standar</span>
                <span>Gratis</span>
            </div>
            <div class="flex justify-between border-t border-slate-200 pt-2 text-base font-bold">
                <span>Total</span>
                <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
        </div>
        <form method="POST" action="{{ route('checkout.process') }}" class="space-y-4">
            @csrf
            <input name="shipping_address" required placeholder="Alamat pengiriman"
                class="w-full rounded border-slate-300">
            <textarea name="notes" placeholder="Catatan (opsional)" class="w-full rounded border-slate-300"></textarea>
            <button class="rounded bg-blue-600 px-4 py-2 text-white">Proses checkout</button>
        </form>
    </main>
</body>

</html>
