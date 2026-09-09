<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'E-Commerce') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 text-gray-800">
        <div class="min-h-screen flex flex-col justify-between">

            <!-- Navbar / Header -->
            <header class="bg-white shadow-sm sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="text-xl font-bold text-indigo-600">🛍️ E-Commerce Store</span>
                    </div>

                    @if (Route::has('login'))
                        <nav class="flex items-center space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="font-semibold text-gray-600 hover:text-indigo-600 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-indigo-600 transition">Log in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="ml-4 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow hover:bg-indigo-500 transition">Register</a>
                                @endif
                            @endauth
                        </nav>
                    @endif
                </div>
            </header>

            <!-- Hero Section -->
            <main class="flex-grow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 flex flex-col md:flex-row items-center justify-between">
                    <div class="md:w-1/2 space-y-6 text-center md:text-left">
                        <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-gray-900">
                            Temukan Produk Terbaik <span class="text-indigo-600">Impian Anda</span>
                        </h1>
                        <p class="text-lg text-gray-600">
                            Belanja mudah, aman, dan cepat. Nikmati berbagai penawaran menarik dan produk berkualitas tinggi setiap hari di toko kami.
                        </p>
                        <div class="flex justify-center md:justify-start space-x-4">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-lg hover:bg-indigo-500 transition">Mulai Belanja</a>
                            @else
                                <a href="{{ route('register') }}" class="px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg shadow-lg hover:bg-indigo-500 transition">Buat Akun</a>
                                <a href="{{ route('login') }}" class="px-6 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-50 transition">Masuk</a>
                            @endif
                        </div>
                    </div>
                    <div class="md:w-1/2 mt-12 md:mt-0 flex justify-center">
                        <div class="w-full max-w-md bg-indigo-100 rounded-2xl p-8 text-center shadow-inner">
                            <span class="text-7xl">🛒</span>
                            <h3 class="mt-4 text-xl font-bold text-indigo-900">Platform E-Commerce Terpercaya</h3>
                            <p class="mt-2 text-sm text-indigo-700">Sistem manajemen produk dan transaksi yang terintegrasi penuh.</p>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 py-6 text-center text-sm text-gray-500">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    &copy; {{ date('Y') }} E-Commerce UTS. All rights reserved.
                </div>
            </footer>
        </div>
    </body>
</html>
