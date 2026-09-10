<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang</title>@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-900 antialiased min-h-screen flex flex-col">
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-bold text-lg text-blue-600">Zenthercraft</a>
            <nav class="flex items-center gap-4">
                <a href="{{ route('home') }}"
                    class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">Beranda</a>
                <a href="{{ route('catalog.index') }}"
                    class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">Katalog</a>
                <a href="{{ route('cart.index') }}" class="text-xl leading-none text-blue-600" title="Keranjang"
                    aria-label="Keranjang">&#128722;</a>
            </nav>
        </div>
    </header>

    <main class="py-12 flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Keranjang Belanja</h1>
                <p class="text-sm text-slate-500 mt-1">Periksa kembali produk sebelum melanjutkan pesanan.</p>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    {{ session('error') }}
                </div>
            @endif

            @if (!$cart || $cart->cartItems->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-12 text-center">
                    <div class="text-4xl mb-4">&#128722;</div>
                    <h2 class="text-lg font-semibold text-slate-900">Keranjang masih kosong</h2>
                    <p class="text-sm text-slate-500 mt-1">Pilih produk dari katalog untuk menyimpannya di sini.</p>
                    <a href="{{ route('catalog.index') }}"
                        class="inline-block mt-6 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 transition">
                        Lihat Katalog
                    </a>
                </div>
            @else
                @php
                    $cartTotal = $cart->cartItems->sum(fn($item) => $item->product->price * $item->quantity);
                @endphp

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                    <div class="lg:col-span-2 space-y-4">
                        @foreach ($cart->cartItems as $item)
                            <div
                                class="bg-white rounded-2xl shadow-sm border border-slate-200 p-4 sm:p-5 flex items-center gap-4">
                                <div
                                    class="w-20 h-20 rounded-xl bg-slate-100 overflow-hidden flex items-center justify-center shrink-0">
                                    @if ($item->product->image)
                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                            alt="{{ $item->product->product_name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xl text-slate-400">&#128722;</span>
                                    @endif
                                </div>
                                <input type="radio" name="cart_item_id" value="{{ $item->cart_item_id }}"
                                    form="selected-cart-checkout" required
                                    data-total="{{ $item->product->price * $item->quantity }}"
                                    class="h-5 w-5 border-slate-300 text-blue-600 focus:ring-blue-500"
                                    title="Pilih produk ini untuk checkout"
                                    aria-label="Pilih {{ $item->product->product_name }} untuk checkout">
                                <div class="min-w-0 flex-1">
                                    <h2 class="font-semibold text-slate-900 truncate">
                                        {{ $item->product->product_name }}</h2>
                                    <p class="text-sm text-slate-500 mt-1">{{ $item->quantity }} x Rp
                                        {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                    <p class="text-sm font-bold text-blue-600 mt-2">Rp
                                        {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</p>
                                </div>
                                <form method="POST" action="{{ route('cart.remove', $item->cart_item_id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xl text-rose-500 hover:text-rose-700 transition"
                                        title="Hapus produk" aria-label="Hapus produk">&#128465;</button>
                                </form>
                            </div>
                        @endforeach
                    </div>

                    <aside class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5">
                        <h2 class="font-bold text-slate-900">Ringkasan Belanja</h2>
                        <div class="mt-4 flex justify-between text-sm text-slate-600">
                            <span>Subtotal pilihan</span>
                            <span id="selected-subtotal">Pilih produk</span>
                        </div>
                        <div class="mt-2 flex justify-between text-sm text-slate-500">
                            <span>Pengiriman standar</span>
                            <span>Gratis</span>
                        </div>
                        <div class="mt-4 border-t border-slate-200 pt-4 flex justify-between font-bold text-slate-900">
                            <span>Total</span>
                            <span id="selected-total" class="text-blue-600">Pilih produk</span>
                        </div>
                        <form id="selected-cart-checkout" method="POST" action="{{ route('cart.checkout-selected') }}"
                            class="mt-5">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700 transition">
                                Checkout Produk Terpilih
                            </button>
                        </form>
                    </aside>
                </div>
            @endif
        </div>
    </main>

    @if ($cart && $cart->cartItems->isNotEmpty())
        <script>
            document.querySelectorAll('input[name="cart_item_id"]').forEach((item) => {
                item.addEventListener('change', () => {
                    const total = Number(item.dataset.total).toLocaleString('id-ID');
                    document.getElementById('selected-subtotal').textContent = `Rp ${total}`;
                    document.getElementById('selected-total').textContent = `Rp ${total}`;
                });
            });
        </script>
    @endif

    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} Zenthercraft. All rights reserved.
    </footer>
</body>

</html>
