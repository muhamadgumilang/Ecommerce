<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 p-6">
    <main class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Keranjang Belanja</h1>
        @if (session('success'))
            <p class="mb-4 text-green-600">{{ session('success') }}</p>
            @endif@if (!$cart || $cart->cartItems->isEmpty())
            <p>Keranjang masih kosong.</p>@else<div class="space-y-3">
                    @foreach ($cart->cartItems as $item)
                        <div class="bg-white rounded p-4 flex justify-between"><span>{{ $item->product->product_name }} x
                                {{ $item->quantity }}</span>
                            <form method="POST" action="{{ route('cart.remove', $item->cart_item_id) }}">@csrf
                                @method('DELETE')<button class="text-red-600">Hapus</button></form>
                        </div>
                    @endforeach
                </div>
                <a class="inline-block mt-6 text-blue-600" href="{{ route('checkout.index') }}">Lanjut checkout</a>
            @endif
    </main>
</body>

</html>
