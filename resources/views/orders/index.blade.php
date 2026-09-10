<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pesanan Saya - {{ config('app.name', 'Zenthercraft') }}</title>

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
                <a href="{{ route('orders.index') }}" class="text-sm font-semibold text-blue-600">
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

            <!-- Page Title -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Pesanan Saya</h1>
                    <p class="text-sm text-slate-500 mt-1">Pantau status pengiriman dan riwayat pesanan Anda di sini.</p>
                </div>

                <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700 shadow-sm shadow-blue-500/20 transition self-start sm:self-auto">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Belanja Lagi</span>
                </a>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Order Cards List -->
            <div class="space-y-4">
                @forelse ($orders as $order)
                    @php
                        $statusStyles = [
                            'Pending Payment' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Processing' => 'bg-blue-50 text-blue-700 border-blue-200',
                            'Shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                            'Completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                            'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                        ];
                        $statusLabels = [
                            'Pending Payment' => 'Menunggu Pembayaran',
                            'Processing' => 'Sedang Diproses',
                            'Shipped' => 'Dalam Pengiriman',
                            'Completed' => 'Pesanan Selesai',
                            'Cancelled' => 'Dibatalkan',
                        ];
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm hover:shadow transition">
                        <!-- Top Order Info -->
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-base text-slate-900">#{{ $order->order_id }}</span>
                                <span class="text-xs text-slate-400">&bull;</span>
                                <span class="text-xs text-slate-500">{{ ($order->order_date ?? $order->created_at)->translatedFormat('d F Y, H:i') }} WIB</span>
                            </div>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border {{ $statusStyles[$order->order_status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $statusLabels[$order->order_status] ?? $order->order_status }}
                            </span>
                        </div>

                        <!-- Product Thumbnails Preview -->
                        <div class="py-4 space-y-3">
                            @foreach ($order->orderDetails->take(2) as $detail)
                                <div class="flex items-center gap-3.5">
                                    <div class="w-14 h-14 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($detail->product && $detail->product->image)
                                            <img src="{{ asset('storage/' . $detail->product->image) }}" alt="{{ $detail->product->product_name }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-lg text-slate-400">&#128722;</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-medium text-slate-800 text-sm truncate">
                                            {{ $detail->product?->product_name ?? 'Produk' }}
                                        </p>
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            {{ $detail->quantity }} barang &times; Rp {{ number_format($detail->product?->price ?? ($detail->subtotal / max(1, $detail->quantity)), 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div class="text-right text-xs font-semibold text-slate-700 shrink-0">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach

                            @if ($order->orderDetails->count() > 2)
                                <p class="text-xs text-slate-400 pl-1">
                                    + {{ $order->orderDetails->count() - 2 }} produk lainnya
                                </p>
                            @endif
                        </div>

                        <!-- Bottom Footer Info & Action -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-t border-slate-100 pt-4">
                            <div>
                                <span class="text-xs text-slate-500">Total Pesanan:</span>
                                <span class="text-base font-bold text-blue-600 ml-1">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                @if ($order->order_status === 'Pending Payment' && (!$order->payment || $order->payment->payment_status === 'Failed'))
                                    <a href="{{ route('payments.create', $order) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold transition shadow-sm shadow-blue-600/20">
                                        Bayar Sekarang
                                    </a>
                                @endif
                                <a href="{{ route('orders.show', $order) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center shadow-sm">
                        <div class="text-4xl mb-4">&#128230;</div>
                        <h2 class="text-lg font-semibold text-slate-900">Belum Ada Pesanan</h2>
                        <p class="text-sm text-slate-500 mt-1">Anda belum memiliki riwayat pesanan apa pun saat ini.</p>
                        <a href="{{ route('catalog.index') }}"
                           class="inline-block mt-6 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 shadow-md shadow-blue-600/20 transition">
                            Mulai Belanja
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} Zenthercraft. All rights reserved.
    </footer>

</body>

</html>
