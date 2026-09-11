<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja - {{ config('app.name', 'E-Commerce') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[72px] flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="font-bold text-lg text-slate-900 flex items-center gap-2 shrink-0">
                <span
                    class="bg-blue-600 text-white w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm shadow-blue-600/25">E</span>
                <span class="tracking-tight">E-Commerce</span>
            </a>

            <nav class="flex items-center gap-1 sm:gap-2 text-sm">
                <a href="{{ route('home') }}"
                    class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                    Beranda
                </a>
                <a href="{{ route('catalog.index') }}"
                    class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                    Katalog
                </a>
                <a href="{{ route('orders.index') }}"
                    class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                    Pesanan Saya
                </a>
                <a href="{{ route('cart.index') }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-blue-50 text-lg leading-none text-blue-600 transition"
                    title="Keranjang" aria-label="Keranjang">
                    &#128722;
                </a>

                @auth
                    @if (Auth::user()->role === 'Admin')
                        <a href="{{ url('/dashboard') }}"
                            class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm shadow-blue-600/20">
                            Admin
                        </a>
                    @else
                        <span class="text-xs text-slate-500 hidden lg:inline px-2">Halo, <strong
                                class="text-slate-800">{{ Auth::user()->name }}</strong></span>
                    @endif
                    <a href="{{ route('profile.edit') }}"
                        class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                        Masuk
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-8 sm:py-10 flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs -->
            <nav class="flex items-center text-sm text-slate-500 space-x-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                <span>&rsaquo;</span>
                <span class="text-slate-900 font-medium">Keranjang Belanja</span>
            </nav>

            <!-- Page Title -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Keranjang Belanja</h1>
                    <p class="text-sm text-slate-500 mt-1">Periksa kembali barang pilihan Anda dan lanjutkan ke proses
                        pemesanan.</p>
                </div>

                @if ($cart && $cart->cartItems->isNotEmpty())
                    <div
                        class="text-xs font-medium text-slate-600 bg-white px-3.5 py-2 rounded-xl shadow-sm border border-slate-200 self-start sm:self-auto">
                        Total Produk di Keranjang: <span
                            class="text-blue-600 font-bold">{{ $cart->cartItems->count() }} item</span>
                        ({{ $cart->cartItems->sum('quantity') }} barang)
                    </div>
                @endif
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div
                    class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div
                    class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if (!$cart || $cart->cartItems->isEmpty())
                <!-- Empty Cart State -->
                <div
                    class="bg-white rounded-2xl border border-slate-200 p-12 sm:p-16 text-center shadow-sm max-w-2xl mx-auto">
                    <div
                        class="w-20 h-20 mx-auto rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-4xl mb-5">
                        &#128722;
                    </div>
                    <h2 class="text-xl font-bold text-slate-900">Keranjang Belanja Masih Kosong</h2>
                    <p class="text-sm text-slate-500 mt-2 max-w-md mx-auto">
                        Anda belum menambahkan produk apa pun ke keranjang. Temukan berbagai penawaran dan produk
                        menarik di katalog kami.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('catalog.index') }}"
                            class="w-full sm:w-auto rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white hover:bg-blue-700 shadow-md shadow-blue-600/20 transition">
                            Jelajahi Katalog Produk
                        </a>
                        <a href="{{ route('orders.index') }}"
                            class="w-full sm:w-auto rounded-xl bg-slate-100 px-6 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">
                            Lihat Riwayat Pesanan
                        </a>
                    </div>
                </div>
            @else
                <!-- Active Cart Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                    <!-- Left: Cart Items List -->
                    <div class="lg:col-span-2 space-y-4">
                        <div
                            class="bg-blue-50/70 border border-blue-100 rounded-2xl p-4 text-xs text-blue-800 flex items-center gap-3">
                            <svg class="w-5 h-5 text-blue-600 shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Pilih produk yang ingin di-checkout, atau gunakan opsi <strong>Checkout Semua</strong>
                                di ringkasan belanja.</span>
                        </div>

                        <!-- Single Checkout Form Target -->
                        <form id="selected-cart-checkout" method="POST"
                            action="{{ route('cart.checkout-selected') }}">
                            @csrf
                        </form>

                        @foreach ($cart->cartItems as $index => $item)
                            <div
                                class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-sm hover:border-blue-300 transition flex flex-col sm:flex-row sm:items-center gap-4 relative group">

                                <!-- Selection Radio -->
                                <div class="flex items-center gap-3 shrink-0">
                                    <label class="cursor-pointer flex items-center">
                                        <input type="radio" name="cart_item_id" value="{{ $item->cart_item_id }}"
                                            form="selected-cart-checkout" @checked($loop->first)
                                            data-name="{{ $item->product->product_name }}"
                                            data-qty="{{ $item->quantity }}"
                                            data-total="{{ $item->product->price * $item->quantity }}"
                                            class="w-5 h-5 text-blue-600 border-slate-300 focus:ring-blue-500 cursor-pointer"
                                            aria-label="Pilih {{ $item->product->product_name }}">
                                    </label>

                                    <!-- Product Thumbnail -->
                                    <div
                                        class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                alt="{{ $item->product->product_name }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @else
                                            <span class="text-2xl text-slate-400">&#128722;</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="min-w-0 flex-1">
                                    <h2 class="font-semibold text-slate-900 text-base leading-snug">
                                        <a href="{{ route('products.show', $item->product) }}"
                                            class="hover:text-blue-600 transition">
                                            {{ $item->product->product_name }}
                                        </a>
                                    </h2>

                                    <div class="flex items-center gap-2 mt-1">
                                        @if ($item->product->category)
                                            <span
                                                class="text-[11px] font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded">
                                                {{ $item->product->category->category_name }}
                                            </span>
                                        @endif
                                        <span class="text-xs text-slate-500">
                                            Harga: <strong class="text-slate-700">Rp
                                                {{ number_format($item->product->price, 0, ',', '.') }}</strong>
                                        </span>
                                    </div>

                                    @if ($item->product->stock <= 5 && $item->product->stock > 0)
                                        <p class="text-[11px] text-amber-600 mt-1 font-medium">
                                            Sisa stok terbatas: hanya tersisa {{ $item->product->stock }} unit!
                                        </p>
                                    @elseif ($item->product->stock <= 0)
                                        <p class="text-[11px] text-rose-600 mt-1 font-semibold">
                                            Stok habis! Harap kurangi atau hapus produk ini.
                                        </p>
                                    @endif

                                    <!-- Quantity Stepper & Price in Mobile/Desktop -->
                                    <div class="mt-3 flex flex-wrap items-center justify-between gap-3">
                                        <!-- Quantity Controls -->
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-slate-400 mr-1 hidden sm:inline">Jumlah:</span>

                                            <!-- Decrement Form -->
                                            <form method="POST"
                                                action="{{ route('cart.update', $item->cart_item_id) }}"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity"
                                                    value="{{ max(1, $item->quantity - 1) }}">
                                                <button type="submit" {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                                    class="w-7 h-7 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-600 hover:bg-slate-200 disabled:opacity-40 disabled:cursor-not-allowed transition font-bold text-sm"
                                                    title="Kurangi jumlah">
                                                    &minus;
                                                </button>
                                            </form>

                                            <span class="w-8 text-center text-sm font-semibold text-slate-800">
                                                {{ $item->quantity }}
                                            </span>

                                            <!-- Increment Form -->
                                            <form method="POST"
                                                action="{{ route('cart.update', $item->cart_item_id) }}"
                                                class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="quantity"
                                                    value="{{ $item->quantity + 1 }}">
                                                <button type="submit"
                                                    {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}
                                                    class="w-7 h-7 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center text-slate-600 hover:bg-slate-200 disabled:opacity-40 disabled:cursor-not-allowed transition font-bold text-sm"
                                                    title="Tambah jumlah">
                                                    &plus;
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Subtotal Calculation -->
                                        <div class="text-right">
                                            <span class="text-xs text-slate-400 block sm:inline mr-1">Subtotal:</span>
                                            <span class="text-base font-bold text-blue-600">
                                                Rp
                                                {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Remove Button -->
                                <div class="self-end sm:self-start pt-1 sm:pt-0">
                                    <form method="POST" action="{{ route('cart.remove', $item->cart_item_id) }}"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition"
                                            title="Hapus produk dari keranjang"
                                            aria-label="Hapus {{ $item->product->product_name }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <!-- Right: Billing & Checkout Summary -->
                    <div class="space-y-6">
                        <aside class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                            <h2 class="font-bold text-slate-900 text-lg border-b border-slate-100 pb-4">
                                Ringkasan Belanja
                            </h2>

                            <div class="mt-4 space-y-3 text-sm">
                                <div>
                                    <p class="text-xs text-slate-400 uppercase font-semibold">Produk Terpilih</p>
                                    <p id="selected-product-name" class="font-medium text-slate-800 truncate mt-0.5">-
                                    </p>
                                </div>

                                <div class="flex justify-between text-slate-600 pt-2 border-t border-slate-100">
                                    <span>Subtotal Pilihan</span>
                                    <span id="selected-subtotal" class="font-semibold text-slate-900">-</span>
                                </div>

                                <div class="flex justify-between text-slate-600">
                                    <span>Estimasi Pengiriman</span>
                                    <span class="text-emerald-600 font-semibold">Gratis</span>
                                </div>

                                <div
                                    class="border-t border-slate-200 pt-3 mt-2 flex justify-between items-center text-base font-bold text-slate-900">
                                    <span>Total Tagihan</span>
                                    <span id="selected-total" class="text-xl text-blue-600">-</span>
                                </div>
                            </div>

                            <!-- Checkout Selected Button -->
                            <div class="mt-6 space-y-3">
                                <button type="submit" form="selected-cart-checkout"
                                    class="w-full rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 active:scale-[0.99] transition flex items-center justify-center gap-2">
                                    <span>Checkout Produk Terpilih</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                    </svg>
                                </button>

                                <!-- Checkout All Form -->
                                <form method="POST" action="{{ route('cart.checkout-all') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-xl bg-slate-100 px-5 py-2.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                                        Checkout Semua ({{ $cart->cartItems->count() }} Produk)
                                    </button>
                                </form>

                                <a href="{{ route('catalog.index') }}"
                                    class="w-full inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-2.5 text-xs font-medium text-slate-600 hover:bg-slate-50 transition">
                                    Lanjut Belanja Produk Lain
                                </a>
                            </div>

                            <p class="text-[11px] text-slate-400 text-center mt-4 leading-relaxed">
                                Ongkos kirim akhir dan alamat penerima akan dikonfirmasi pada halaman checkout.
                            </p>
                        </aside>

                        <!-- Trust & Assurance Card -->
                        <div
                            class="bg-gradient-to-br from-blue-50 to-sky-50 rounded-2xl border border-blue-100 p-5 space-y-3 text-xs text-slate-600">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">Keamanan Transaksi</p>
                                    <p class="text-slate-500 mt-0.5">Pembayaran Anda dilindungi sistem verifikasi
                                        pesanan yang terpercaya.</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3 pt-2 border-t border-blue-100">
                                <div
                                    class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900">Pengiriman Cepat & Terpercaya</p>
                                    <p class="text-slate-500 mt-0.5">Pesanan langsung dikemas dan dikirim ke alamat
                                        tujuan Anda.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            @endif

        </div>
    </main>

    @if ($cart && $cart->cartItems->isNotEmpty())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const radioInputs = document.querySelectorAll('input[name="cart_item_id"]');
                const selectedSubtotal = document.getElementById('selected-subtotal');
                const selectedTotal = document.getElementById('selected-total');
                const selectedProductName = document.getElementById('selected-product-name');

                function updateSummary(input) {
                    if (!input) return;
                    const total = Number(input.dataset.total).toLocaleString('id-ID');
                    const qty = input.dataset.qty;
                    const name = input.dataset.name;

                    if (selectedSubtotal) selectedSubtotal.textContent = `Rp ${total}`;
                    if (selectedTotal) selectedTotal.textContent = `Rp ${total}`;
                    if (selectedProductName) selectedProductName.textContent = `${name} (${qty} barang)`;
                }

                // Inisialisasi awal pada item yang terpilih
                const checkedRadio = document.querySelector('input[name="cart_item_id"]:checked') || radioInputs[0];
                if (checkedRadio) {
                    checkedRadio.checked = true;
                    updateSummary(checkedRadio);
                }

                radioInputs.forEach((item) => {
                    item.addEventListener('change', () => {
                        updateSummary(item);
                    });
                });
            });
        </script>
    @endif

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} E-Commerce. All rights reserved.
    </footer>

</body>

</html>
