<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout Pesanan - {{ config('app.name', 'Zenthercraft') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white">

    <!-- Header / Navbar -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-bold text-lg text-blue-600 flex items-center gap-2">
                <span class="bg-blue-600 text-white w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm shadow-sm">Z</span>
                <span>Zenthercraft</span>
            </a>

            <nav class="flex items-center gap-4 sm:gap-6">
                <a href="{{ route('home') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                    Beranda
                </a>
                <a href="{{ route('catalog.index') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                    Katalog
                </a>
                <a href="{{ route('orders.index') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                    Pesanan Saya
                </a>
                <a href="{{ route('cart.index') }}" class="text-xl leading-none text-slate-600 hover:text-blue-600 transition" title="Keranjang" aria-label="Keranjang">
                    &#128722;
                </a>

                @auth
                    @if (Auth::user()->role === 'Admin')
                        <a href="{{ url('/dashboard') }}" class="px-3 py-1.5 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition">
                            Admin
                        </a>
                    @else
                        <span class="text-xs text-slate-500 hidden md:inline">Halo, <strong class="text-slate-800">{{ Auth::user()->name }}</strong></span>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs font-medium text-red-600 hover:text-red-700 transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-blue-600 transition">
                        Masuk
                    </a>
                @endauth
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-8 sm:py-10 flex-1">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs -->
            <div class="flex items-center justify-between">
                <nav class="flex items-center text-sm text-slate-500 space-x-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('cart.index') }}" class="hover:text-blue-600 transition">Keranjang</a>
                    <span>&rsaquo;</span>
                    <span class="text-slate-900 font-medium">Checkout</span>
                </nav>

                <a href="{{ route('cart.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Keranjang</span>
                </a>
            </div>

            <!-- Page Title -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Checkout Pesanan</h1>
                <p class="text-sm text-slate-500 mt-1">Lengkapi informasi alamat pengiriman dan konfirmasi pesanan Anda.</p>
            </div>

            <!-- Flash Error Messages -->
            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm">
                    <div class="flex items-center gap-2 font-semibold">
                        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Terdapat kesalahan pada formulir checkout:</span>
                    </div>
                    <ul class="list-disc list-inside mt-2 space-y-1 text-xs text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Checkout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- Left 2 Columns: Shipping Address & Order Items -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Form Alamat Pengiriman -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-sm">
                        <h2 class="font-bold text-slate-900 text-lg flex items-center gap-2 border-b border-slate-100 pb-4 mb-5">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Informasi & Alamat Pengiriman</span>
                        </h2>

                        <!-- Recipient Profile Info -->
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div>
                                <span class="text-slate-400 block font-medium">Nama Pemesan</span>
                                <span class="font-bold text-slate-800 text-sm mt-0.5 block">{{ Auth::user()->name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-medium">Kontak / Email</span>
                                <span class="font-medium text-slate-700 mt-0.5 block">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="sm:text-right">
                                <span class="text-slate-400 block font-medium">Metode Kurir</span>
                                <span class="inline-flex items-center gap-1 font-semibold text-emerald-600 mt-0.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                                    Pengiriman Standar (Gratis)
                                </span>
                            </div>
                        </div>

                        <!-- Checkout Form -->
                        <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}" class="space-y-4">
                            @csrf

                            <div>
                                <label for="shipping_address" class="block text-sm font-semibold text-slate-800 mb-1.5">
                                    Alamat Lengkap Pengiriman <span class="text-rose-500">*</span>
                                </label>
                                <textarea id="shipping_address"
                                          name="shipping_address"
                                          rows="3"
                                          required
                                          placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, dan kode pos tujuan..."
                                          class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm placeholder:text-slate-400 p-3.5">{{ old('shipping_address') }}</textarea>
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5">
                                    Catatan Tambahan untuk Kurir (Opsional)
                                </label>
                                <input type="text"
                                       id="notes"
                                       name="notes"
                                       value="{{ old('notes') }}"
                                       placeholder="Contoh: Titipkan di pos satpam, atau pagar warna hitam..."
                                       class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm placeholder:text-slate-400 p-3">
                            </div>
                        </form>
                    </div>

                    <!-- Produk yang Diproses -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-sm">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                            <h2 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span>Produk yang Dipesan</span>
                            </h2>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">
                                {{ $cart->cartItems->count() }} Produk
                            </span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach ($cart->cartItems as $item)
                                <div class="py-4 first:pt-0 last:pb-0 flex items-center gap-4">
                                    <div class="w-16 h-16 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                 alt="{{ $item->product->product_name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <span class="text-xl text-slate-400">&#128722;</span>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-slate-900 text-sm truncate">
                                            {{ $item->product->product_name }}
                                        </h3>
                                        <div class="flex items-center gap-2 mt-1 text-xs text-slate-500">
                                            <span>Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                            <span>&times;</span>
                                            <span class="font-medium text-slate-700">{{ $item->quantity }} barang</span>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-xs text-slate-400 block">Subtotal</span>
                                        <span class="font-bold text-slate-900 text-sm">
                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

                <!-- Right Column: Billing Summary & Confirmation -->
                <div class="space-y-6">

                    <aside class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <h2 class="font-bold text-slate-900 text-lg border-b border-slate-100 pb-4 mb-4">
                            Ringkasan Tagihan
                        </h2>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-slate-600">
                                <span>Subtotal Belanja</span>
                                <span class="font-medium text-slate-900">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between text-slate-600">
                                <span>Pengiriman Standar</span>
                                <span class="font-semibold text-emerald-600">Gratis</span>
                            </div>

                            <div class="border-t border-slate-200 pt-3 mt-3 flex justify-between items-center text-base">
                                <span class="font-bold text-slate-900">Total Pembayaran</span>
                                <span class="font-bold text-xl text-blue-600">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-6 space-y-3">
                            <button type="submit"
                                    form="checkout-form"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 active:scale-[0.99] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span>Proses Checkout Pesanan</span>
                            </button>

                            <a href="{{ route('cart.index') }}"
                               class="w-full inline-flex items-center justify-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-200 transition">
                                Kembali ke Keranjang
                            </a>
                        </div>

                        <p class="text-[11px] text-slate-400 text-center mt-4 leading-relaxed">
                            Dengan memproses pesanan, Anda menyetujui syarat & ketentuan transaksi di Zenthercraft.
                        </p>
                    </aside>

                    <!-- Trust Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-sky-50 rounded-2xl border border-blue-100 p-5 space-y-3 text-xs text-slate-600">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">Garansi Pesanan Terpercaya</p>
                                <p class="text-slate-500 mt-0.5">Produk dijamin sampai di tujuan dengan aman dan bergaransi.</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} Zenthercraft. All rights reserved.
    </footer>

</body>

</html>
