<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pembayaran Pesanan #{{ $order->order_id }} - {{ config('app.name', 'Zenthercraft') }}</title>

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
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs & Back Button -->
            <div class="flex items-center justify-between">
                <nav class="flex items-center text-sm text-slate-500 space-x-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('orders.index') }}" class="hover:text-blue-600 transition">Pesanan Saya</a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('orders.show', $order) }}" class="hover:text-blue-600 transition">#{{ $order->order_id }}</a>
                    <span>&rsaquo;</span>
                    <span class="text-slate-900 font-medium">Pembayaran</span>
                </nav>

                <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Detail Pesanan</span>
                </a>
            </div>

            <!-- Page Title -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Pembayaran Pesanan #{{ $order->order_id }}</h1>
                <p class="text-sm text-slate-500 mt-1">Pilih saluran pembayaran yang sesuai untuk memproses pesanan Anda.</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm">
                    <div class="flex items-center gap-2 font-semibold">
                        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Terdapat kendala:</span>
                    </div>
                    <ul class="list-disc list-inside mt-2 space-y-1 text-xs text-rose-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 2-Column Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- Left 2 Cols: Payment Method Selection -->
                <div class="lg:col-span-2 space-y-6">

                    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-sm">
                        <h2 class="font-bold text-slate-900 text-lg flex items-center gap-2 border-b border-slate-100 pb-4 mb-5">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            <span>Pilih Saluran Pembayaran</span>
                        </h2>

                        <form id="payment-form" method="POST" action="{{ route('payments.store', $order) }}" class="space-y-4">
                            @csrf

                            <!-- Option 1: Bank Transfer -->
                            <label class="payment-card flex items-start gap-4 p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-500 transition cursor-pointer bg-white">
                                <input type="radio" name="payment_method" value="Bank Transfer" checked
                                       class="mt-1 w-5 h-5 text-blue-600 border-slate-300 focus:ring-blue-500">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="font-bold text-slate-900 text-sm">Transfer Bank Manual / Virtual Account</h3>
                                        <span class="text-[11px] font-semibold text-blue-600 bg-blue-50 px-2 py-0.5 rounded">Populer</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">BCA, Mandiri, BNI, BRI. Cepat dan mudah diverifikasi.</p>

                                    <div class="mt-3 p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs text-slate-700 space-y-1">
                                        <p class="font-semibold text-slate-800">Nomor Rekening Tujuan:</p>
                                        <p class="text-blue-700 font-mono font-bold text-sm tracking-wider">BCA: 8720-1928-3341</p>
                                        <p class="text-slate-500">a.n PT Zenthercraft Indonesia</p>
                                    </div>
                                </div>
                            </label>

                            <!-- Option 2: E-Wallet -->
                            <label class="payment-card flex items-start gap-4 p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-500 transition cursor-pointer bg-white">
                                <input type="radio" name="payment_method" value="E-Wallet"
                                       class="mt-1 w-5 h-5 text-blue-600 border-slate-300 focus:ring-blue-500">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="font-bold text-slate-900 text-sm">Dompet Digital (E-Wallet / QRIS)</h3>
                                        <span class="text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Instan</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">GoPay, OVO, DANA, ShopeePay, LinkAja via QRIS Nasional.</p>

                                    <div class="mt-3 p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs text-slate-700">
                                        <p class="text-slate-600">Scan kode QRIS yang tersedia pada struk pembayaran atau konfirmasi via WhatsApp customer service.</p>
                                    </div>
                                </div>
                            </label>

                            <!-- Option 3: COD -->
                            <label class="payment-card flex items-start gap-4 p-4 rounded-2xl border-2 border-slate-200 hover:border-blue-500 transition cursor-pointer bg-white">
                                <input type="radio" name="payment_method" value="COD"
                                       class="mt-1 w-5 h-5 text-blue-600 border-slate-300 focus:ring-blue-500">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="font-bold text-slate-900 text-sm">Cash on Delivery (Bayar di Tempat)</h3>
                                        <span class="text-[11px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded">Bayar Nanti</span>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-1">Bayar secara tunai ke kurir saat barang sampai di alamat rumah Anda.</p>
                                </div>
                            </label>

                        </form>
                    </div>

                </div>

                <!-- Right 1 Col: Order Summary & Pay Button -->
                <div class="space-y-6">

                    <aside class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <h2 class="font-bold text-slate-900 text-lg border-b border-slate-100 pb-4 mb-4">
                            Ringkasan Tagihan
                        </h2>

                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between text-slate-600">
                                <span>Total Belanja ({{ $order->orderDetails->sum('quantity') }} barang)</span>
                                <span class="font-medium text-slate-900">
                                    Rp {{ number_format($order->orderDetails->sum('subtotal'), 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between text-slate-600">
                                <span>Biaya Pengiriman</span>
                                <span class="font-semibold text-emerald-600">Gratis</span>
                            </div>

                            <div class="border-t border-slate-200 pt-3 mt-3 flex justify-between items-center text-base">
                                <span class="font-bold text-slate-900">Total Pembayaran</span>
                                <span class="font-bold text-xl text-blue-600">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-6 space-y-3">
                            <button type="submit"
                                    form="payment-form"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 active:scale-[0.99] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Konfirmasi & Kirim Pembayaran</span>
                            </button>

                            <a href="{{ route('orders.show', $order) }}"
                               class="w-full inline-flex items-center justify-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-200 transition">
                                Kembali ke Detail Pesanan
                            </a>
                        </div>

                        <p class="text-[11px] text-slate-400 text-center mt-4 leading-relaxed">
                            Setelah mengirim pembayaran, status pesanan akan diproses dan diverifikasi oleh tim kami.
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
                                <p class="font-bold text-slate-900">Pembayaran Terenkripsi</p>
                                <p class="text-slate-500 mt-0.5">Seluruh data transaksi dan data pengguna disimpan secara aman.</p>
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
