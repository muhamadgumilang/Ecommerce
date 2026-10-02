<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout Pesanan - {{ config('app.name', 'E-Commerce') }}</title>

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
                <x-cart-link />
                <x-notification-menu />
                <x-wishlist-link />

                @auth
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-semibold rounded-lg hover:bg-blue-700 transition shadow-sm shadow-blue-600/20">
                            Admin
                        </a>
                    @elseif (Auth::user()->isSeller())
                        <a href="{{ route('seller.dashboard') }}"
                            class="inline-flex items-center gap-1.5 px-3 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700 transition shadow-sm shadow-emerald-600/20">
                            <span>🏪</span> Toko Saya
                        </a>
                    @else
                        <span class="text-xs text-slate-500 hidden lg:inline px-2">Halo, <strong
                                class="text-slate-800">{{ Auth::user()->name }}</strong></span>
                    @endif
                    <x-profile-menu />
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

            <!-- Breadcrumbs -->
            <div class="flex items-center justify-between">
                <nav class="flex items-center text-sm text-slate-500 space-x-2">
                    <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                    <span>&rsaquo;</span>
                    <a href="{{ route('cart.index') }}" class="hover:text-blue-600 transition">Keranjang</a>
                    <span>&rsaquo;</span>
                    <span class="text-slate-900 font-medium">Checkout</span>
                </nav>

                <a href="{{ route('cart.index') }}"
                    class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Kembali ke Keranjang</span>
                </a>
            </div>

            <!-- Page Title -->
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Checkout Pesanan</h1>
                <p class="text-sm text-slate-500 mt-1">Lengkapi informasi alamat pengiriman dan konfirmasi pesanan Anda.
                </p>
            </div>

            <!-- Flash Error Messages -->
            @if ($errors->any())
                <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800 shadow-sm">
                    <div class="flex items-center gap-2 font-semibold">
                        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
                        <h2
                            class="font-bold text-slate-900 text-lg flex items-center gap-2 border-b border-slate-100 pb-4 mb-5">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Informasi & Alamat Pengiriman</span>
                        </h2>

                        <!-- Recipient Profile Info -->
                        <div
                            class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                            <div>
                                <span class="text-slate-400 block font-medium">Nama Pemesan</span>
                                <span
                                    class="font-bold text-slate-800 text-sm mt-0.5 block">{{ Auth::user()->name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block font-medium">Kontak / Email</span>
                                <span class="font-medium text-slate-700 mt-0.5 block">{{ Auth::user()->email }}</span>
                            </div>
                            <div class="sm:text-right">
                                <span class="text-slate-400 block font-medium">Metode Kurir</span>
                                <span class="inline-flex items-center gap-1 font-semibold text-emerald-600 mt-0.5">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                                    Asal: {{ $originAddress ?? 'Dayeuhkolot, Cibedug, RT 4 RW 2' }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-5 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-xs text-blue-700">
                            <span class="font-semibold">Estimasi berat pesanan:</span>
                            {{ number_format($totalWeight ?? 0, 0, ',', '.') }} gram
                        </div>

                        <!-- Checkout Form -->
                        <form id="checkout-form" method="POST" action="{{ route('checkout.process') }}"
                            class="space-y-4">
                            @csrf

                            <!-- Cek Ongkir ala RajaOngkir -->
                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 sm:p-5 shadow-inner shadow-slate-100/80">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label
                                            class="block text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2">Dari</label>
                                        <div
                                            class="flex h-12 items-center rounded-xl border border-slate-200 bg-white px-3.5 text-sm font-medium text-slate-800 shadow-sm">
                                            {{ $originAddress ?? 'Dayeuhkolot, Cibedug, RT 4 RW 2' }}
                                        </div>
                                    </div>
                                    <div>
                                        <label for="province_id"
                                            class="block text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2">Provinsi</label>
                                        <select id="province_id" name="destination_province" required
                                            class="h-12 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                            <option value="">Pilih provinsi</option>
                                            <option value="11">ACEH</option>
                                            <option value="12">SUMATERA UTARA</option>
                                            <option value="13">SUMATERA BARAT</option>
                                            <option value="14">RIAU</option>
                                            <option value="15">JAMBI</option>
                                            <option value="16">SUMATERA SELATAN</option>
                                            <option value="17">BENGKULU</option>
                                            <option value="18">LAMPUNG</option>
                                            <option value="19">KEPULAUAN BANGKA BELITUNG</option>
                                            <option value="21">KEPULAUAN RIAU</option>
                                            <option value="31">DKI JAKARTA</option>
                                            <option value="32">JAWA BARAT</option>
                                            <option value="33">JAWA TENGAH</option>
                                            <option value="34">JAWA TIMUR</option>
                                            <option value="35">DI YOGYAKARTA</option>
                                            <option value="36">BANTEN</option>
                                            <option value="51">BALI</option>
                                            <option value="52">NUSA TENGGARA BARAT</option>
                                            <option value="53">NUSA TENGGARA TIMUR</option>
                                            <option value="61">KALIMANTAN BARAT</option>
                                            <option value="62">KALIMANTAN TENGAH</option>
                                            <option value="63">KALIMANTAN SELATAN</option>
                                            <option value="64">KALIMANTAN TIMUR</option>
                                            <option value="65">KALIMANTAN UTARA</option>
                                            <option value="71">SULAWESI UTARA</option>
                                            <option value="72">SULAWESI TENGAH</option>
                                            <option value="73">SULAWESI SELATAN</option>
                                            <option value="74">SULAWESI TENGGARA</option>
                                            <option value="75">GORONTALO</option>
                                            <option value="76">SULAWESI BARAT</option>
                                            <option value="81">MALUKU</option>
                                            <option value="82">MALUKU UTARA</option>
                                            <option value="91">PAPUA BARAT</option>
                                            <option value="92">PAPUA</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2">Berat</label>
                                        <div
                                            class="flex h-12 items-center rounded-xl border border-slate-200 bg-white px-3.5 text-sm font-medium text-slate-800 shadow-sm">
                                            {{ number_format($totalWeight ?? 0, 0, ',', '.') }} gram
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" id="courier" name="courier" required>
                                <input type="hidden" id="service" name="service" required>
                                <input type="hidden" id="destination" name="destination" value="531" required>

                                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="destination_regency"
                                            class="block text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2">Kabupaten/Kota</label>
                                        <select id="destination_regency" name="destination_regency" required disabled
                                            class="h-12 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400">
                                            <option value="">Pilih provinsi terlebih dahulu</option>
                                        </select>
                                        <input id="destination_regency_manual" name="destination_regency"
                                            type="text" required disabled hidden placeholder="Ketik kabupaten/kota"
                                            class="h-12 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    </div>
                                    <div>
                                        <label for="destination_district"
                                            class="block text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2">Kecamatan</label>
                                        <select id="destination_district" name="destination_district" required
                                            disabled
                                            class="h-12 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400">
                                            <option value="">Pilih kabupaten/kota terlebih dahulu</option>
                                        </select>
                                        <input id="destination_district_manual" name="destination_district"
                                            type="text" required disabled hidden placeholder="Ketik kecamatan"
                                            class="h-12 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    </div>
                                    <div>
                                        <label for="destination_village"
                                            class="block text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2">Desa/Kelurahan</label>
                                        <select id="destination_village" name="destination_village" required disabled
                                            class="h-12 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-800 shadow-sm transition focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-400">
                                            <option value="">Pilih kecamatan terlebih dahulu</option>
                                        </select>
                                        <input id="destination_village_manual" name="destination_village"
                                            type="text" required disabled hidden placeholder="Ketik desa/kelurahan"
                                            class="h-12 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-800 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    </div>
                                    <div>
                                        <label for="postal_code"
                                            class="block text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-500 mb-2">Kode
                                            Pos</label>
                                        <input type="text" id="postal_code" name="postal_code" required
                                            inputmode="numeric" pattern="[0-9]{5}" maxlength="5"
                                            value="{{ old('postal_code') }}" placeholder="Otomatis / isi 5 digit"
                                            class="h-12 w-full rounded-xl border border-slate-300 bg-white px-3 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
                                    </div>
                                </div>

                                <div
                                    class="mt-5 flex flex-col gap-3 border-t border-slate-200 pt-5 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <h3 class="font-bold text-slate-900">Pilih ekspedisi</h3>
                                        <p class="mt-1 text-xs text-slate-500">Harga dihitung berdasarkan tujuan dan
                                            berat pesanan.</p>
                                    </div>
                                    <span id="selected-shipping-label"
                                        class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1.5 text-[11px] font-semibold text-amber-700">Belum
                                        dipilih</span>
                                </div>

                                <div id="shipping-options"
                                    class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                    @foreach ($shippingCosts as $shipping)
                                        <button type="button"
                                            class="shipping-option flex h-full flex-col justify-between rounded-2xl border border-slate-200 bg-white p-4 text-left shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-amber-400 hover:shadow-md"
                                            data-courier="{{ $shipping['courier'] }}"
                                            data-service="{{ $shipping['service'] }}"
                                            data-destination="{{ $shipping['destination'] }}"
                                            data-cost="{{ $shipping['cost'] }}"
                                            data-description="{{ $shipping['description'] }}"
                                            data-etd="{{ $shipping['etd'] }}">
                                            <div class="flex items-start justify-between gap-2">
                                                <span
                                                    class="font-bold uppercase text-slate-900">{{ $shipping['courier'] }}</span>
                                                <span
                                                    class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-500">COD
                                                    & NON COD</span>
                                            </div>
                                            <p class="mt-4 text-xs text-slate-500">{{ $shipping['description'] }}</p>
                                            <p class="mt-2 text-lg font-bold text-slate-900">Rp
                                                {{ number_format($shipping['cost'], 0, ',', '.') }}</p>
                                            <p class="mt-1 text-[11px] text-slate-400">Estimasi {{ $shipping['etd'] }}
                                                hari</p>
                                            <span
                                                class="mt-4 block rounded-xl bg-amber-400 px-3 py-2 text-center text-[11px] font-bold text-slate-950">Pilih
                                                layanan</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <label for="shipping_address"
                                    class="block text-sm font-semibold text-slate-800 mb-1.5">
                                    Alamat Lengkap Pengiriman <span class="text-rose-500">*</span>
                                </label>
                                <textarea id="shipping_address" name="shipping_address" rows="3" required
                                    placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota/kabupaten, dan kode pos tujuan..."
                                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm placeholder:text-slate-400 p-3.5">{{ old('shipping_address') }}</textarea>
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-slate-700 mb-1.5">
                                    Catatan Tambahan untuk Kurir (Opsional)
                                </label>
                                <input type="text" id="notes" name="notes" value="{{ old('notes') }}"
                                    placeholder="Contoh: Titipkan di pos satpam, atau pagar warna hitam..."
                                    class="w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm placeholder:text-slate-400 p-3">
                            </div>
                        </form>
                    </div>

                    <!-- Produk yang Diproses -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-sm">
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
                                {{ $cart->cartItems->count() }} Produk
                            </span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach ($cart->cartItems as $item)
                                <div class="py-4 first:pt-0 last:pb-0 flex items-center gap-4">
                                    <div
                                        class="w-16 h-16 rounded-xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center">
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
                                            <span class="font-medium text-slate-700">{{ $item->quantity }}
                                                barang</span>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        <span class="text-xs text-slate-400 block">Subtotal</span>
                                        <span class="font-bold text-slate-900 text-sm">
                                            Rp
                                            {{ number_format(round((float) $item->product->price) * $item->quantity, 0, ',', '.') }}
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
                                <span>Ongkir</span>
                                <span class="font-semibold text-emerald-600" id="shipping-fee-display">
                                    Rp 0
                                </span>
                            </div>

                            <div
                                class="border-t border-slate-200 pt-3 mt-3 flex justify-between items-center text-base">
                                <span class="font-bold text-slate-900">Total Pembayaran</span>
                                <span class="font-bold text-xl text-blue-600" id="total-payment">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="mt-6 space-y-3">
                            <button type="submit" form="checkout-form"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-semibold text-white shadow-md shadow-blue-600/20 hover:bg-blue-700 active:scale-[0.99] transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                                <span>Proses Checkout Pesanan</span>
                            </button>

                            <a href="{{ route('cart.index') }}"
                                class="w-full inline-flex items-center justify-center rounded-xl bg-slate-100 px-5 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-200 transition">
                                Kembali ke Keranjang
                            </a>
                        </div>

                        <p class="text-[11px] text-slate-400 text-center mt-4 leading-relaxed">
                            Dengan memproses pesanan, Anda menyetujui syarat & ketentuan transaksi di E-Commerce.
                        </p>
                    </aside>

                    <!-- Trust Card -->
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
                                <p class="font-bold text-slate-900">Garansi Pesanan Terpercaya</p>
                                <p class="text-slate-500 mt-0.5">Produk dijamin sampai di tujuan dengan aman dan
                                    bergaransi.</p>
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

    <script>
        const destinationInput = document.getElementById('destination');
        const provinceSelect = document.getElementById('province_id');
        const regencySelect = document.getElementById('destination_regency');
        const districtSelect = document.getElementById('destination_district');
        const villageSelect = document.getElementById('destination_village');
        const regencyManualInput = document.getElementById('destination_regency_manual');
        const districtManualInput = document.getElementById('destination_district_manual');
        const villageManualInput = document.getElementById('destination_village_manual');
        const postalCodeInput = document.getElementById('postal_code');
        const courierInput = document.getElementById('courier');
        const serviceInput = document.getElementById('service');
        let shippingOptions = [...document.querySelectorAll('.shipping-option')];

        const locationApi = @json(route('shipping.locations'));
        const costApi = @json(route('shipping.cost'));
        const defaultOrigin = @json(config('services.rajaongkir.origin', ''));
        const initialShippingOptions = @json($shippingCosts ?? []);
        const fallbackLocations = {
            province: [{
                    id: '11',
                    name: 'ACEH'
                },
                {
                    id: '12',
                    name: 'SUMATERA UTARA'
                },
                {
                    id: '13',
                    name: 'SUMATERA BARAT'
                },
                {
                    id: '14',
                    name: 'RIAU'
                },
                {
                    id: '15',
                    name: 'JAMBI'
                },
                {
                    id: '16',
                    name: 'SUMATERA SELATAN'
                },
                {
                    id: '17',
                    name: 'BENGKULU'
                },
                {
                    id: '18',
                    name: 'LAMPUNG'
                },
                {
                    id: '19',
                    name: 'KEPULAUAN BANGKA BELITUNG'
                },
                {
                    id: '21',
                    name: 'KEPULAUAN RIAU'
                },
                {
                    id: '31',
                    name: 'DKI JAKARTA'
                },
                {
                    id: '32',
                    name: 'JAWA BARAT'
                },
                {
                    id: '33',
                    name: 'JAWA TENGAH'
                },
                {
                    id: '34',
                    name: 'JAWA TIMUR'
                },
                {
                    id: '35',
                    name: 'DI YOGYAKARTA'
                },
                {
                    id: '36',
                    name: 'BANTEN'
                },
                {
                    id: '51',
                    name: 'BALI'
                },
                {
                    id: '52',
                    name: 'NUSA TENGGARA BARAT'
                },
                {
                    id: '53',
                    name: 'NUSA TENGGARA TIMUR'
                },
                {
                    id: '61',
                    name: 'KALIMANTAN BARAT'
                },
                {
                    id: '62',
                    name: 'KALIMANTAN TENGAH'
                },
                {
                    id: '63',
                    name: 'KALIMANTAN SELATAN'
                },
                {
                    id: '64',
                    name: 'KALIMANTAN TIMUR'
                },
                {
                    id: '65',
                    name: 'KALIMANTAN UTARA'
                },
                {
                    id: '71',
                    name: 'SULAWESI UTARA'
                },
                {
                    id: '72',
                    name: 'SULAWESI TENGAH'
                },
                {
                    id: '73',
                    name: 'SULAWESI SELATAN'
                },
                {
                    id: '74',
                    name: 'SULAWESI TENGGARA'
                },
                {
                    id: '75',
                    name: 'GORONTALO'
                },
                {
                    id: '76',
                    name: 'SULAWESI BARAT'
                },
                {
                    id: '81',
                    name: 'MALUKU'
                },
                {
                    id: '82',
                    name: 'MALUKU UTARA'
                },
                {
                    id: '91',
                    name: 'PAPUA BARAT'
                },
                {
                    id: '92',
                    name: 'PAPUA'
                },
            ],
            regency: {
                '31': [{
                        id: '3101',
                        name: 'KOTA JAKARTA PUSAT'
                    },
                    {
                        id: '3171',
                        name: 'KOTA JAKARTA SELATAN'
                    },
                    {
                        id: '3172',
                        name: 'KOTA JAKARTA BARAT'
                    },
                    {
                        id: '3173',
                        name: 'KOTA JAKARTA TIMUR'
                    },
                ],
                '32': [{
                        id: '3204',
                        name: 'KABUPATEN BANDUNG'
                    },
                    {
                        id: '3273',
                        name: 'KOTA BANDUNG'
                    },
                    {
                        id: '3201',
                        name: 'KABUPATEN BOGOR'
                    },
                    {
                        id: '3275',
                        name: 'KOTA BEKASI'
                    },
                ],
                '36': [{
                        id: '3601',
                        name: 'KABUPATEN PANDEGLANG'
                    },
                    {
                        id: '3671',
                        name: 'KOTA TANGERANG'
                    },
                    {
                        id: '3674',
                        name: 'KOTA TANGERANG SELATAN'
                    },
                ],
            },
            district: {
                '3204': [{
                        id: '320425',
                        name: 'DAYEUHKOLOT'
                    },
                    {
                        id: '320401',
                        name: 'BANDUNG'
                    },
                    {
                        id: '320426',
                        name: 'CILEUNYI'
                    },
                    {
                        id: '320427',
                        name: 'UJUNGBERUNG'
                    },
                ],
                '3273': [{
                        id: '327302',
                        name: 'BANDUNG KULON'
                    },
                    {
                        id: '327303',
                        name: 'BANDUNG WETAN'
                    },
                    {
                        id: '327304',
                        name: 'ANDIR'
                    },
                ],
                '3671': [{
                        id: '367101',
                        name: 'TANGERANG'
                    },
                    {
                        id: '367102',
                        name: 'CIPUTAT'
                    },
                ],
            },
            village: {
                '320425': [{
                        id: '3204251001',
                        name: 'CIBEDUG'
                    },
                    {
                        id: '3204251002',
                        name: 'DAYEUHKOLOT'
                    },
                    {
                        id: '3204251003',
                        name: 'NAGRAK'
                    },
                ],
                '367101': [{
                        id: '3671011001',
                        name: 'TANGERANG'
                    },
                    {
                        id: '3671011002',
                        name: 'CIPONDOH'
                    },
                ],
            }
        };

        function normalizeLocations(payload) {
            if (Array.isArray(payload)) {
                return payload;
            }

            if (payload && Array.isArray(payload.data)) {
                return payload.data;
            }

            if (payload && Array.isArray(payload.results)) {
                return payload.results;
            }

            return [];
        }

        function getFallbackLocations(type, parentId = null) {
            if (type === 'province') {
                return fallbackLocations.province;
            }

            if (!parentId) {
                return [];
            }

            return fallbackLocations[type]?.[parentId] ?? [];
        }

        function setSelectOptions(select, items, placeholder) {
            const safeItems = Array.isArray(items) ? items : [];
            select.innerHTML = `<option value="">${placeholder}</option>`;
            safeItems.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                option.dataset.name = item.name;
                select.appendChild(option);
            });
            select.disabled = safeItems.length === 0;

            const manualInput = document.getElementById(`${select.id}_manual`);
            if (manualInput) {
                manualInput.hidden = safeItems.length > 0;
                manualInput.disabled = safeItems.length > 0;
                manualInput.required = safeItems.length === 0;
            }
        }

        function seedFallbackSelects() {
            setSelectOptions(provinceSelect, getFallbackLocations('province'), 'Pilih provinsi');
            provinceSelect.value = '32';

            const regencyFallback = getFallbackLocations('regency', '32');
            setSelectOptions(regencySelect, regencyFallback, 'Pilih kabupaten/kota');
            if (regencyFallback.length) {
                regencySelect.value = regencyFallback[0].id;
            }

            const districtFallback = getFallbackLocations('district', regencyFallback[0]?.id || '3204');
            setSelectOptions(districtSelect, districtFallback, 'Pilih kecamatan');
            if (districtFallback.length) {
                districtSelect.value = districtFallback[0].id;
            }

            const villageFallback = getFallbackLocations('village', districtFallback[0]?.id || '320425');
            setSelectOptions(villageSelect, villageFallback, 'Pilih desa/kelurahan');
            if (villageFallback.length) {
                villageSelect.value = villageFallback[0].id;
            }

            destinationInput.value = '531';
        }

        async function fetchJsonWithTimeout(url, timeoutMs = 2500) {
            const controller = new AbortController();
            const timer = setTimeout(() => controller.abort(), timeoutMs);

            try {
                const response = await fetch(url, {
                    signal: controller.signal,
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });

                if (response.redirected || response.type === 'opaqueredirect') {
                    throw new Error('Request redirected to login or unavailable resource.');
                }

                const text = await response.text();

                if (!response.ok) {
                    throw new Error(`Request failed with status ${response.status}: ${text.slice(0, 120)}`);
                }

                if (!text) {
                    return null;
                }

                try {
                    return JSON.parse(text);
                } catch (parseError) {
                    throw new Error(`Invalid JSON response: ${text.slice(0, 120)}`);
                }
            } finally {
                clearTimeout(timer);
            }
        }

        async function loadLocations(url, select, placeholder, type = null, parentId = null) {
            const params = new URLSearchParams(url.split('?')[1] || '');
            const resolvedType = type || params.get('type');
            const resolvedParentId = parentId ?? params.get('parent');
            const selectedValue = select.value;
            const fallback = getFallbackLocations(resolvedType, resolvedParentId);

            if (fallback.length) {
                setSelectOptions(select, fallback, placeholder);
            } else {
                select.disabled = true;
                select.innerHTML = `<option value="">Tidak tersedia</option>`;
            }

            try {
                const payload = await fetchJsonWithTimeout(url, 900);
                const locations = normalizeLocations(payload);
                const data = Array.isArray(locations) && locations.length ? locations : fallback;

                if (Array.isArray(data) && data.length) {
                    setSelectOptions(select, data, placeholder);
                    select.value = data.some((item) => String(item.id) === selectedValue) ?
                        selectedValue :
                        String(data[0].id);
                    if (select.value !== selectedValue) {
                        select.dispatchEvent(new Event('change'));
                    }
                    return data;
                }

                setSelectOptions(select, fallback, placeholder);
                return fallback;
            } catch (error) {
                if (fallback.length) {
                    setSelectOptions(select, fallback, placeholder);
                    return fallback;
                }

                select.disabled = true;
                select.innerHTML = `<option value="">Tidak tersedia</option>`;
                return [];
            }
        }

        function setFallbackLocations() {
            setSelectOptions(provinceSelect, getFallbackLocations('province'), 'Pilih provinsi');
            setSelectOptions(regencySelect, getFallbackLocations('regency', '32'), 'Pilih kabupaten/kota');
            setSelectOptions(districtSelect, getFallbackLocations('district', '3204'), 'Pilih kecamatan');
            setSelectOptions(villageSelect, getFallbackLocations('village', '320425'), 'Pilih desa/kelurahan');
            postalCodeInput.value = postalCodeInput.value || '40257';
        }

        async function loadProvinces() {
            const fallback = getFallbackLocations('province');
            setSelectOptions(provinceSelect, fallback, 'Pilih provinsi');

            try {
                const provinces = await loadLocations(`${locationApi}?type=province`, provinceSelect, 'Pilih provinsi',
                    'province');
                if (!provinces.length) {
                    setFallbackLocations();
                    return;
                }

                const westJava = provinces.find((province) => province.name.toLowerCase().includes('jawa barat'));
                if (westJava) {
                    provinceSelect.value = westJava.id;
                    provinceSelect.dispatchEvent(new Event('change'));
                }
            } catch (error) {
                setFallbackLocations();
            }
        }

        provinceSelect.addEventListener('change', async () => {
            if (!provinceSelect.value) {
                setSelectOptions(regencySelect, [], 'Pilih provinsi terlebih dahulu');
                setSelectOptions(districtSelect, [], 'Pilih kabupaten/kota terlebih dahulu');
                setSelectOptions(villageSelect, [], 'Pilih kecamatan terlebih dahulu');
                return;
            }

            const fallback = getFallbackLocations('regency', provinceSelect.value);
            setSelectOptions(regencySelect, fallback, 'Pilih kabupaten/kota');
            if (fallback.length) {
                regencySelect.value = fallback[0].id;
                regencySelect.dispatchEvent(new Event('change'));
            } else {
                setSelectOptions(districtSelect, [], 'Pilih kabupaten/kota terlebih dahulu');
                setSelectOptions(villageSelect, [], 'Pilih kecamatan terlebih dahulu');
            }

            await loadLocations(`${locationApi}?type=regency&parent=${provinceSelect.value}`, regencySelect,
                'Pilih kabupaten/kota', 'regency', provinceSelect.value);
        });

        regencySelect.addEventListener('change', async () => {
            if (!regencySelect.value) {
                setSelectOptions(districtSelect, [], 'Pilih kabupaten/kota terlebih dahulu');
                setSelectOptions(villageSelect, [], 'Pilih kecamatan terlebih dahulu');
                return;
            }

            destinationInput.value = regencySelect.value;

            const fallback = getFallbackLocations('district', regencySelect.value);
            setSelectOptions(districtSelect, fallback, 'Pilih kecamatan');
            if (fallback.length) {
                districtSelect.value = fallback[0].id;
                districtSelect.dispatchEvent(new Event('change'));
            } else {
                setSelectOptions(villageSelect, [], 'Pilih kecamatan terlebih dahulu');
            }

            await loadLocations(`${locationApi}?type=district&parent=${regencySelect.value}`, districtSelect,
                'Pilih kecamatan', 'district', regencySelect.value);
            await refreshShippingOptions();
        });

        regencyManualInput.addEventListener('change', () => {
            destinationInput.value = regencyManualInput.value.trim();
            refreshShippingOptions();
        });

        districtSelect.addEventListener('change', async () => {
            if (!districtSelect.value) {
                setSelectOptions(villageSelect, [], 'Pilih kecamatan terlebih dahulu');
                return;
            }

            const fallback = getFallbackLocations('village', districtSelect.value);
            setSelectOptions(villageSelect, fallback, 'Pilih desa/kelurahan');
            if (fallback.length) {
                villageSelect.value = fallback[0].id;
                villageSelect.dispatchEvent(new Event('change'));
            }

            await loadLocations(`${locationApi}?type=village&parent=${districtSelect.value}`, villageSelect,
                'Pilih desa/kelurahan', 'village', districtSelect.value);
        });

        villageSelect.addEventListener('change', async () => {
            const villageName = villageSelect.options[villageSelect.selectedIndex]?.textContent
                ?.toLowerCase() || '';
            if (villageName.includes('cibedug') && !postalCodeInput.value) postalCodeInput.value = '40257';

        });

        function normalizeShippingOption(option, fallbackCourier = null) {
            const courier = (option?.courier ?? fallbackCourier ?? '').toLowerCase();
            const service = (option?.service ?? '').toUpperCase();
            const description = option?.description ?? `${courier.toUpperCase()} ${service}`;

            const normalizedCost = Array.isArray(option?.cost) ?
                (Number(option.cost[0]?.value ?? option.cost[0]?.cost ?? 0)) :
                Number(option?.cost ?? option?.price ?? 0);

            const etdValue = Number(option?.etd ?? (option?.cost && Array.isArray(option.cost) ? option.cost[0]?.etd?.match(
                /\d+/)?.[0] ?? 3 : 3));

            return {
                courier,
                service,
                description,
                cost: Number.isFinite(normalizedCost) ? normalizedCost : 0,
                etd: Number.isFinite(etdValue) ? etdValue : 3,
                destination: option?.destination ?? (destinationInput.value || ''),
            };
        }

        function buildShippingCard(option) {
            const normalized = normalizeShippingOption(option, option?.courier ?? 'jne');
            return `
                <button type="button"
                    class="shipping-option text-left rounded-xl border border-slate-200 bg-white p-4 transition hover:-translate-y-0.5 hover:border-amber-400 hover:shadow-md"
                    data-courier="${normalized.courier}"
                    data-service="${normalized.service}"
                    data-destination="${normalized.destination}"
                    data-cost="${normalized.cost}"
                    data-description="${normalized.description}"
                    data-etd="${normalized.etd}">
                    <div class="flex items-start justify-between gap-2">
                        <span class="font-bold uppercase text-slate-900">${normalized.courier}</span>
                        <span class="rounded-full bg-slate-100 px-2 py-1 text-[10px] font-semibold text-slate-500">COD & NON COD</span>
                    </div>
                    <p class="mt-5 text-xs text-slate-500">${normalized.description}</p>
                    <p class="mt-1 text-lg font-bold text-slate-900">Rp ${Number(normalized.cost).toLocaleString('id-ID')}</p>
                    <p class="mt-1 text-[11px] text-slate-400">Estimasi ${normalized.etd} hari</p>
                    <span class="mt-3 block rounded-lg bg-amber-400 px-3 py-2 text-center text-xs font-bold text-slate-950">Pilih layanan</span>
                </button>
            `;
        }

        function renderShippingOptions(options) {
            const optionList = Array.isArray(options) ? options : Object.values(options ?? {});
            const fallbackList = Array.isArray(initialShippingOptions) ?
                initialShippingOptions :
                Object.values(initialShippingOptions ?? {});
            const list = optionList.length ? optionList : fallbackList;
            const parsed = list.map((option) => normalizeShippingOption(option, option?.courier ?? 'jne'));

            const container = document.getElementById('shipping-options');
            container.innerHTML = parsed.map(buildShippingCard).join('');

            shippingOptions = [...container.querySelectorAll('.shipping-option')];
            shippingOptions.forEach((option) => {
                option.addEventListener('click', () => selectShipping(option));
            });

            filterShippingOptions();
        }

        async function refreshShippingOptions() {
            const destination = destinationInput.value;
            if (!destination) return;
            const weight = {{ $totalWeight ?? 0 }};
            const couriers = ['jne', 'tiki', 'pos'];

            try {
                const requests = couriers.map(async (courier) => {
                    const url =
                        `${costApi}?origin=${encodeURIComponent(defaultOrigin || 'dayeuhkolot-cibedug-rt4-rw2')}&destination=${encodeURIComponent(destination)}&weight=${weight}&courier=${encodeURIComponent(courier)}`;
                    const payload = await fetchJsonWithTimeout(url, 1800);
                    return Array.isArray(payload) ? payload : [];
                });

                const results = await Promise.allSettled(requests);
                const flattened = results
                    .filter((result) => result.status === 'fulfilled')
                    .flatMap((result) => Array.isArray(result.value) ? result.value : [])
                    .filter(Boolean);

                if (flattened.length) {
                    renderShippingOptions(flattened);
                    return;
                }
            } catch (error) {
                console.error('Shipping cost refresh failed:', error);
            }

            renderShippingOptions(initialShippingOptions);
        }

        function selectShipping(card) {
            shippingOptions.forEach((option) => option.classList.remove('border-amber-500', 'ring-2', 'ring-amber-200',
                'bg-amber-50'));
            card.classList.add('border-amber-500', 'ring-2', 'ring-amber-200', 'bg-amber-50');
            courierInput.value = card.dataset.courier;
            serviceInput.value = card.dataset.service;
            document.getElementById('selected-shipping-label').textContent = `${card.dataset.description} dipilih`;
            const foundCost = Number(card.dataset.cost);
            document.getElementById('shipping-fee-display').textContent = 'Rp ' + foundCost.toLocaleString('id-ID');
            updateTotal(foundCost);
        }

        function filterShippingOptions() {
            const destination = destinationInput.value;
            const visible = shippingOptions.filter((option) => option.dataset.destination === destination);
            shippingOptions.forEach((option) => {
                option.classList.toggle('hidden', option.dataset.destination !== destination);
            });
            if (visible.length) selectShipping(visible[0]);
        }

        function updateTotal(shippingFee) {
            const subtotal = {{ $subtotal }};
            const total = subtotal + shippingFee;
            document.getElementById('total-payment').textContent =
                'Rp ' + total.toLocaleString('id-ID');
        }

        renderShippingOptions(initialShippingOptions);
        filterShippingOptions();
        seedFallbackSelects();
        loadProvinces();
    </script>
</body>

</html>
