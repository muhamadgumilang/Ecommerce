<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pesanan #{{ $order->order_id }} - {{ config('app.name', 'E-Commerce') }}</title>

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
                <a href="{{ route('home') }}" class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                    Beranda
                </a>
                <a href="{{ route('catalog.index') }}"
                    class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-medium text-slate-600 hover:bg-slate-100 hover:text-blue-600 transition">
                    Katalog
                </a>
                <a href="{{ route('orders.index') }}" class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg font-semibold text-blue-600 bg-blue-50">
                    Pesanan Saya
                </a>
                <a href="{{ route('cart.index') }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-slate-100 text-lg leading-none text-slate-600 hover:bg-blue-50 hover:text-blue-600 transition" title="Keranjang"
                    aria-label="Keranjang">
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
                        <button type="submit" class="inline-flex items-center px-3 py-2 rounded-lg text-xs font-medium text-red-600 hover:bg-red-50 hover:text-red-700 transition">
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
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs & Back Button -->
            <div class="flex items-center justify-between">
                <nav class="flex items-center text-sm text-slate-500 space-x-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('orders.index') }}" class="hover:text-blue-600 transition">Pesanan Saya</a>
                    <span>&rsaquo;</span>
                    <span class="text-slate-900 font-medium">#{{ $order->order_id }}</span>
                </nav>

                <a href="{{ route('orders.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Daftar Pesanan</span>
                </a>
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

            <!-- Order Header Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-sm">
                <div
                    class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-5">
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pesanan
                                #{{ $order->order_id }}</h1>
                            @php
                                $statusStyles = [
                                    'Pending Payment' =>
                                        'bg-amber-50 text-amber-700 border-amber-200 ring-amber-500/20',
                                    'Processing' => 'bg-blue-50 text-blue-700 border-blue-200 ring-blue-500/20',
                                    'Shipped' => 'bg-indigo-50 text-indigo-700 border-indigo-200 ring-indigo-500/20',
                                    'Completed' =>
                                        'bg-emerald-50 text-emerald-700 border-emerald-200 ring-emerald-500/20',
                                    'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200 ring-rose-500/20',
                                ];
                                $statusLabels = [
                                    'Pending Payment' => 'Menunggu Pembayaran',
                                    'Processing' => 'Sedang Diproses',
                                    'Shipped' => 'Dalam Pengiriman',
                                    'Completed' => 'Pesanan Selesai',
                                    'Cancelled' => 'Dibatalkan',
                                ];
                                $currentStyle =
                                    $statusStyles[$order->order_status] ??
                                    'bg-slate-50 text-slate-700 border-slate-200';
                                $currentLabel = $statusLabels[$order->order_status] ?? $order->order_status;
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border ring-1 {{ $currentStyle }}">
                                {{ $currentLabel }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 mt-1">
                            Dipesan pada: <span
                                class="font-medium text-slate-700">{{ ($order->order_date ?? $order->created_at)->translatedFormat('d F Y, H:i') }}
                                WIB</span>
                        </p>
                    </div>

                    <div class="flex items-center gap-2 self-start sm:self-center">
                        <span class="text-xs text-slate-500">Total Pembayaran:</span>
                        <span class="text-xl font-bold text-blue-600">Rp
                            {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Order Status Stepper -->
                @if ($order->order_status !== 'Cancelled')
                    @php
                        $steps = [
                            ['key' => 'Pending Payment', 'label' => 'Pesanan Dibuat', 'desc' => 'Menunggu Pembayaran'],
                            ['key' => 'Processing', 'label' => 'Diproses', 'desc' => 'Sedang disiapkan'],
                            ['key' => 'Shipped', 'label' => 'Dikirim', 'desc' => 'Dalam perjalanan'],
                            ['key' => 'Completed', 'label' => 'Selesai', 'desc' => 'Pesanan diterima'],
                        ];
                        $statusOrder = [
                            'Pending Payment' => 1,
                            'Processing' => 2,
                            'Shipped' => 3,
                            'Completed' => 4,
                        ];
                        $currentStepIndex = $statusOrder[$order->order_status] ?? 1;
                    @endphp

                    <div class="pt-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach ($steps as $index => $step)
                                @php
                                    $stepNum = $index + 1;
                                    $isPassed = $stepNum <= $currentStepIndex;
                                    $isCurrent = $stepNum === $currentStepIndex;
                                @endphp
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0 transition-colors {{ $isPassed ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'bg-slate-100 text-slate-400 border border-slate-200' }}">
                                        @if ($stepNum < $currentStepIndex)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        @else
                                            {{ $stepNum }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p
                                            class="text-xs font-semibold {{ $isCurrent ? 'text-blue-600' : ($isPassed ? 'text-slate-800' : 'text-slate-400') }}">
                                            {{ $step['label'] }}
                                        </p>
                                        <p class="text-[11px] text-slate-500 truncate">{{ $step['desc'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div
                        class="mt-4 p-4 rounded-xl bg-rose-50 border border-rose-100 flex items-center gap-3 text-sm text-rose-700">
                        <svg class="w-5 h-5 shrink-0 text-rose-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>Pesanan ini telah dibatalkan. Jika Anda memiliki pertanyaan atau kendala, silakan hubungi
                            admin.</span>
                    </div>
                @endif
            </div>

            <!-- 2-Column Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

                <!-- Left Column (Items & Delivery Info) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Daftar Produk -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-4">
                            <h2 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <span>Produk yang Dipesan</span>
                            </h2>
                            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg">
                                {{ $order->orderDetails->count() }} Produk
                            </span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse ($order->orderDetails as $detail)
                                <div class="py-4 first:pt-0 last:pb-0 flex items-center gap-4">
                                    <!-- Product Thumbnail -->
                                    <div
                                        class="w-20 h-20 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if ($detail->product && $detail->product->image)
                                            <img src="{{ asset('storage/' . $detail->product->image) }}"
                                                alt="{{ $detail->product->product_name }}"
                                                class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl text-slate-400">&#128722;</span>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-slate-900 text-base truncate">
                                            @if ($detail->product)
                                                <a href="{{ route('products.show', $detail->product) }}"
                                                    class="hover:text-blue-600 transition">
                                                    {{ $detail->product->product_name }}
                                                </a>
                                            @else
                                                <span class="text-slate-500">Produk Tidak Tersedia</span>
                                            @endif
                                        </h3>
                                        @if ($detail->product?->category)
                                            <p class="text-xs text-slate-400 mt-0.5">Kategori:
                                                {{ $detail->product->category->category_name }}</p>
                                        @endif
                                        <div class="flex items-center gap-2 mt-2 text-sm text-slate-500">
                                            <span>Rp
                                                {{ number_format($detail->product?->price ?? $detail->subtotal / max(1, $detail->quantity), 0, ',', '.') }}</span>
                                            <span>&times;</span>
                                            <span
                                                class="px-2 py-0.5 bg-slate-100 rounded text-xs font-medium text-slate-700">{{ $detail->quantity }}
                                                barang</span>
                                        </div>
                                    </div>

                                    <!-- Subtotal Item -->
                                    <div class="text-right shrink-0">
                                        <p class="text-xs text-slate-400">Subtotal</p>
                                        <p class="font-bold text-slate-900 text-base mt-0.5">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400">
                                    <p>Tidak ada detail produk untuk pesanan ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Informasi Pengiriman -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <h2
                            class="font-bold text-slate-900 text-lg flex items-center gap-2 border-b border-slate-100 pb-4 mb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Informasi Pengiriman</span>
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Penerima</p>
                                <p class="font-medium text-slate-900 mt-1">
                                    {{ $order->customer?->name ?? Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    {{ $order->customer?->email ?? Auth::user()->email }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Metode Kurir
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <span
                                        class="font-medium text-slate-900">{{ $order->checkout?->courier ?? 'Pengiriman Standar' }}</span>
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Alamat Tujuan
                                </p>
                                <p
                                    class="text-slate-800 bg-slate-50 p-3.5 rounded-xl border border-slate-100 mt-1.5 leading-relaxed">
                                    {{ $order->checkout?->shipping_address ?? 'Alamat pengiriman belum dicantumkan.' }}
                                </p>
                            </div>

                            @if ($order->checkout?->notes)
                                <div class="sm:col-span-2">
                                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Catatan
                                        Pesanan</p>
                                    <p
                                        class="text-slate-600 italic bg-amber-50/50 p-3 rounded-xl border border-amber-100 mt-1.5 text-xs">
                                        "{{ $order->checkout->notes }}"
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Informasi Pembayaran -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                        <h2
                            class="font-bold text-slate-900 text-lg flex items-center gap-2 border-b border-slate-100 pb-4 mb-4">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            <span>Informasi Pembayaran</span>
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Metode
                                    Pembayaran</p>
                                <p class="font-medium text-slate-900 mt-1">
                                    {{ $order->payment?->payment_method ?? 'Belum Dipilih' }}</p>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Status
                                    Pembayaran</p>
                                <div class="mt-1">
                                    @if ($order->payment)
                                        @if ($order->payment->payment_status === 'Verified')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Lunas / Terverifikasi
                                            </span>
                                        @elseif ($order->payment->payment_status === 'Pending')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif ($order->payment->payment_status === 'Failed')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                Gagal
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-50 text-slate-700 border border-slate-200">
                                                {{ $order->payment->payment_status }}
                                            </span>
                                        @endif
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                            Belum Ada Pembayaran
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Waktu
                                    Pembayaran</p>
                                <p class="text-slate-700 mt-1">
                                    {{ $order->payment?->payment_date ? \Carbon\Carbon::parse($order->payment->payment_date)->translatedFormat('d M Y, H:i') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Right Column (Billing Summary & Actions) -->
                <div class="space-y-6">

                    <!-- Ringkasan Tagihan Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
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
                                <span class="font-medium text-slate-900">
                                    @php
                                        $shippingFee = $order->checkout?->shipping_fee ?? 0;
                                    @endphp
                                    @if ($shippingFee > 0)
                                        Rp {{ number_format($shippingFee, 0, ',', '.') }}
                                    @else
                                        <span class="text-emerald-600 font-semibold">Gratis</span>
                                    @endif
                                </span>
                            </div>

                            <div
                                class="border-t border-slate-200 pt-3 mt-3 flex justify-between items-center text-base">
                                <span class="font-bold text-slate-900">Total Tagihan</span>
                                <span class="font-bold text-xl text-blue-600">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 space-y-3">
                            @if ($order->order_status === 'Pending Payment' && (!$order->payment || $order->payment->payment_status === 'Failed'))
                                <a href="{{ route('payments.create', $order) }}"
                                    class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 active:scale-[0.99] transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>Bayar Sekarang</span>
                                </a>
                                <p class="text-[11px] text-center text-slate-400">
                                    Silakan lakukan pembayaran agar pesanan segera diproses.
                                </p>
                            @elseif ($order->payment && $order->payment->payment_status === 'Pending')
                                <div
                                    class="rounded-xl bg-amber-50 border border-amber-200 p-3.5 text-xs text-amber-800 flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Bukti pembayaran sedang diverifikasi oleh admin. Kami akan memperbarui status
                                        Anda segera.</span>
                                </div>
                            @elseif ($order->payment && $order->payment->payment_status === 'Verified')
                                <div
                                    class="rounded-xl bg-emerald-50 border border-emerald-200 p-3.5 text-xs text-emerald-800 flex items-start gap-2.5">
                                    <svg class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>Pembayaran telah berhasil diverifikasi! Pesanan siap diproses.</span>
                                </div>
                            @endif

                            <a href="{{ route('orders.index') }}"
                                class="w-full inline-flex items-center justify-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition">
                                Daftar Pesanan Saya
                            </a>

                            <a href="{{ route('catalog.index') }}"
                                class="w-full inline-flex items-center justify-center rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 transition">
                                Lanjut Belanja
                            </a>
                        </div>
                    </div>

                    <!-- Bantuan & Layanan Card -->
                    <div class="bg-gradient-to-br from-blue-50 to-sky-50 rounded-2xl border border-blue-100 p-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 text-sm">Butuh Bantuan?</h3>
                                <p class="text-xs text-slate-500 mt-0.5">Jika pesanan bermasalah, silakan hubungi
                                    customer service kami.</p>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        &copy; {{ date('Y') }} E-Commerce. All rights reserved.
    </footer>

</body>

</html>
