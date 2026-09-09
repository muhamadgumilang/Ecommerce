<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Welcome</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts & Styles (Tailwind) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-800">
        <!-- Wrapper Utama -->
        <div class="min-h-screen flex flex-col justify-between">

            <!-- Header / Navbar Atas -->
            <header class="bg-white shadow-sm border-b border-blue-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                    <!-- Logo / Judul Brand -->
                    <div class="flex items-center space-x-3">
                        <div class="bg-blue-600 text-white p-2 rounded-xl font-bold text-base shadow-sm shadow-blue-200">
                            E-Commerce
                        </div>
                        <span class="font-semibold text-slate-700 text-base hidden sm:inline">Store System</span>
                    </div>

                    <!-- Tombol Navigasi Kanan (Auth Check) -->
                    <nav class="flex items-center space-x-4">
                        @auth
                            @if(Auth::user()->role === 'Admin')
                                <a href="{{ url('/dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none transition shadow-sm shadow-blue-200">
                                    Admin Dashboard
                                </a>
                            @else
                                <span class="text-sm font-medium text-slate-600">Halo, {{ Auth::user()->name }}</span>
                            @endif

                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700 transition-colors ml-2">
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-800 transition-colors px-3 py-2">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none transition shadow-md shadow-blue-200">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                </div>
            </header>

            <!-- Konten Utama (Hero Section Tema Biru Putih Bersih) -->
            <main class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
                <div class="max-w-3xl w-full text-center bg-white p-8 sm:p-14 rounded-3xl shadow-xl shadow-blue-900/5 border border-blue-50">
                    <!-- Badge Kecil -->
                    <div class="inline-flex items-center space-x-2 bg-blue-50 text-blue-700 text-xs font-bold px-3.5 py-1.5 rounded-full uppercase tracking-wider mb-6 border border-blue-100">
                        <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                        <span>Platform E-Commerce Terpadu</span>
                    </div>

                    <!-- Judul Utama -->
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 tracking-tight mb-6 leading-tight">
                        Temukan Kebutuhan Terbaik & <span class="text-blue-600">Kelola Toko</span> dengan Mudah
                    </h1>

                    <!-- Deskripsi -->
                    <p class="text-base sm:text-lg text-slate-600 mb-10 max-w-xl mx-auto leading-relaxed">
                        Sistem manajemen berbasis web yang dirancang cepat, responsif, dan nyaman digunakan oleh siapa saja.
                    </p>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        @auth
                            @if(Auth::user()->role === 'Admin')
                                <a href="{{ url('/dashboard') }}" class="px-8 py-3.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all text-sm tracking-wide">
                                    Buka Panel Admin Dashboard
                                </a>
                            @else
                                <div class="p-4 bg-blue-50 rounded-xl text-blue-800 text-sm font-medium border border-blue-100">
                                    Anda sudah masuk sebagai <strong>{{ Auth::user()->name }}</strong>. Jelajahi aplikasi dengan nyaman!
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="px-8 py-3.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all text-sm tracking-wide">
                                Masuk / Login Sekarang
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-8 py-3.5 bg-white text-blue-600 border-2 border-blue-200 font-semibold rounded-xl hover:bg-blue-50 hover:border-blue-300 transition-all text-sm tracking-wide">
                                    Daftar Akun Baru
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-blue-100 py-6 text-center text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </footer>

        </div>
    </body>
</html>
