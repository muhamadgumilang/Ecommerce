<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <!-- Layout Wrapper dengan Sidebar di Kiri -->
        <div class="flex h-screen bg-slate-50">

            <!-- 1. SIDEBAR KIRI -->
            <aside class="w-64 bg-white text-slate-700 min-h-screen p-4 flex flex-col justify-between hidden md:flex shadow-lg border-r border-blue-100">
                <div>
                    <!-- Logo / Judul Brand -->
                    <div class="flex items-center gap-3 h-16 border-b border-blue-100 mb-6 px-2">
                        <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-sky-400 flex items-center justify-center font-bold text-white shadow-lg shadow-blue-200">
                            A
                        </div>
                        <span class="text-lg font-bold tracking-wide text-blue-900">Admin System</span>
                    </div>

                    <!-- Daftar Menu Sidebar -->
                    <nav class="space-y-1.5">
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}"
                           class="group flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-blue-500 to-sky-400 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-700' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                            </svg>
                            <span class="text-sm font-medium">Dashboard</span>
                        </a>

                        <!-- Kategori -->
                        <a href="{{ route('admin.categories.index') }}"
                           class="group flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-gradient-to-r from-blue-500 to-sky-400 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-700' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.categories.*') ? 'text-white' : 'text-blue-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span class="text-sm font-medium">Kategori</span>
                        </a>

                        <!-- Produk -->
                        <a href="{{ route('admin.products.index') }}"
                           class="group flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.products.*') ? 'bg-gradient-to-r from-blue-500 to-sky-400 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-700' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.products.*') ? 'text-white' : 'text-blue-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="text-sm font-medium">Produk</span>
                        </a>

                        <!-- Pesanan / Order -->
                        <a href="{{ route('admin.orders.index') }}"
                           class="group flex items-center px-4 py-2.5 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.orders.*') ? 'bg-gradient-to-r from-blue-500 to-sky-400 text-white shadow-lg shadow-blue-200' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-700' }}">
                            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.orders.*') ? 'text-white' : 'text-blue-400 group-hover:text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                            <span class="text-sm font-medium">Pesanan</span>
                        </a>
                    </nav>
                </div>

                <!-- Bagian Bawah Sidebar (User & Logout) -->
                <div class="border-t border-blue-100 pt-4">
                    <div class="flex items-center gap-3 px-2 mb-3">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-sky-300 flex items-center justify-center text-sm font-bold text-white">
                            {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-semibold text-slate-800 truncate">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-blue-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-4 py-2.5 text-red-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition-colors text-sm font-medium">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Log Out
                        </button>
                    </form>
                </div>
            </aside>

            <!-- 2. KONTEN UTAMA DI SEBELAH KANAN -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Page Heading (Jika ada) -->
                @isset($header)
                    <header class="bg-white border-b border-slate-200 shadow-sm">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
                    {{ $slot }}
                </main>
            </div>

        </div>
    </body>
</html>
