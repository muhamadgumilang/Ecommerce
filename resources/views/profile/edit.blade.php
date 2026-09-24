<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Profil Saya - {{ config('app.name', 'E-Commerce') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-slate-50 font-sans text-slate-800 antialiased min-h-screen flex flex-col justify-between selection:bg-blue-600 selection:text-white">

    <!-- Header / Navbar Utama -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-[72px] flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="font-bold text-lg text-slate-900 flex items-center gap-2 shrink-0">
                <span
                    class="bg-blue-600 text-white w-9 h-9 rounded-xl flex items-center justify-center font-bold text-sm shadow-sm shadow-blue-600/25">Z</span>
                <span class="tracking-tight text-blue-600 font-bold">E-Commerce</span>
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

    <!-- Konten Utama Halaman Profil (Tanpa Sidebar Admin) -->
    <main class="py-8 sm:py-10 flex-1">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Breadcrumbs -->
            <nav class="flex items-center text-sm text-slate-500 space-x-2">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                <span>&rsaquo;</span>
                <span class="text-slate-900 font-medium">Pengaturan Profil</span>
            </nav>

            <!-- Card Banner Identitas User -->
            <div
                class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600 to-sky-500 text-white font-bold text-2xl flex items-center justify-center shadow-md shadow-blue-500/20">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2.5">
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-900">{{ Auth::user()->name }}</h1>
                            @if (Auth::user()->isAdmin())
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 border border-blue-200">
                                    Administrator
                                </span>
                            @elseif (Auth::user()->isSeller())
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    Mitra Penjual (Seller)
                                </span>
                            @else
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                    Pelanggan (Customer)
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-1">{{ Auth::user()->email }} @if (Auth::user()->phone)
                                &bull; {{ Auth::user()->phone }}
                            @endif
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @if (Auth::user()->isSeller())
                        <a href="{{ route('seller.dashboard') }}"
                            class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 border border-emerald-200">
                            <span>🏪</span> Ke Dashboard Toko
                        </a>
                    @elseif (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 border border-blue-200">
                            <span>⚙️</span> Ke Dashboard Admin
                        </a>
                    @else
                        <a href="{{ route('orders.index') }}"
                            class="px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-xs font-semibold transition inline-flex items-center gap-1.5 border border-blue-200">
                            <span>📦</span> Pesanan Saya
                        </a>
                    @endif
                </div>
            </div>

            <!-- Flash Status Messages -->
            @if (session('status') === 'profile-updated')
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm flex items-center gap-3 shadow-sm">
                    <span class="text-emerald-600 text-lg">✓</span>
                    <span>Informasi profil Anda berhasil diperbarui.</span>
                </div>
            @endif

            @if (session('status') === 'password-updated')
                <div
                    class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-sm flex items-center gap-3 shadow-sm">
                    <span class="text-emerald-600 text-lg">✓</span>
                    <span>Kata sandi akun Anda berhasil diperbarui.</span>
                </div>
            @endif

            <!-- 1. Form Update Informasi Profil -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                <div class="max-w-2xl">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span>👤</span> Informasi Profil Akun
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Perbarui nama lengkap, alamat email, dan nomor kontak akun Anda.
                        </p>
                    </div>

                    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                        @csrf
                        @method('patch')

                        <!-- Nama -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input id="name" name="name" type="text"
                                class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5 shadow-sm"
                                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                            @error('name')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <input id="email" name="email" type="email"
                                class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5 shadow-sm"
                                value="{{ old('email', $user->email) }}" required autocomplete="username" />
                            @error('email')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-slate-700 mb-1">
                                Nomor Telepon / WhatsApp
                            </label>
                            <input id="phone" name="phone" type="text"
                                class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5 shadow-sm"
                                value="{{ old('phone', $user->phone) }}" placeholder="Contoh: 08123456789" />
                            @error('phone')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="pt-2 flex items-center justify-end">
                            <button type="submit"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm shadow-blue-500/20 transition">
                                Simpan Perubahan Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. Form Update Password -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                <div class="max-w-2xl">
                    <div class="border-b border-slate-100 pb-4 mb-6">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <span>🔒</span> Perbarui Kata Sandi
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Pastikan akun Anda menggunakan kata sandi yang aman dan tidak mudah ditebak.
                        </p>
                    </div>

                    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                        @csrf
                        @method('put')

                        <!-- Password Saat Ini -->
                        <div>
                            <label for="update_password_current_password"
                                class="block text-sm font-semibold text-slate-700 mb-1">
                                Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                            </label>
                            <input id="update_password_current_password" name="current_password" type="password"
                                class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5 shadow-sm"
                                autocomplete="current-password" placeholder="••••••••" />
                            @if ($errors->updatePassword->get('current_password'))
                                <p class="mt-1 text-xs text-rose-500">
                                    {{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <!-- Password Baru -->
                        <div>
                            <label for="update_password_password"
                                class="block text-sm font-semibold text-slate-700 mb-1">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input id="update_password_password" name="password" type="password"
                                class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5 shadow-sm"
                                autocomplete="new-password" placeholder="Minimal 8 karakter" />
                            @if ($errors->updatePassword->get('password'))
                                <p class="mt-1 text-xs text-rose-500">{{ $errors->updatePassword->first('password') }}
                                </p>
                            @endif
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div>
                            <label for="update_password_password_confirmation"
                                class="block text-sm font-semibold text-slate-700 mb-1">
                                Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input id="update_password_password_confirmation" name="password_confirmation"
                                type="password"
                                class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5 shadow-sm"
                                autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
                            @if ($errors->updatePassword->get('password_confirmation'))
                                <p class="mt-1 text-xs text-rose-500">
                                    {{ $errors->updatePassword->first('password_confirmation') }}</p>
                            @endif
                        </div>

                        <div class="pt-2 flex items-center justify-end">
                            <button type="submit"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm shadow-blue-500/20 transition">
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 3. Form Hapus Akun -->
            <div class="bg-white rounded-2xl border border-rose-100 p-6 sm:p-8 shadow-sm">
                <div class="max-w-2xl">
                    <div class="border-b border-rose-100 pb-4 mb-5">
                        <h2 class="text-lg font-bold text-rose-700 flex items-center gap-2">
                            <span>⚠️</span> Hapus Akun
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Setelah akun Anda dihapus, seluruh data riwayat belanja dan profil akan dihapus secara
                            permanen.
                        </p>
                    </div>

                    <form method="post" action="{{ route('profile.destroy') }}"
                        onsubmit="return confirm('Apakah Anda benar-benar yakin ingin menghapus akun Anda secara permanen?')">
                        @csrf
                        @method('delete')

                        <div class="space-y-4">
                            <div>
                                <label for="delete_password" class="block text-sm font-semibold text-slate-700 mb-1">
                                    Masukkan Kata Sandi untuk Konfirmasi Penghapusan
                                </label>
                                <input id="delete_password" name="password" type="password"
                                    class="w-full sm:w-80 rounded-xl border-slate-300 focus:border-rose-500 focus:ring-rose-500 text-sm py-2.5 px-3.5 shadow-sm"
                                    placeholder="Kata sandi akun Anda" required />
                                @if ($errors->userDeletion->get('password'))
                                    <p class="mt-1 text-xs text-rose-500">
                                        {{ $errors->userDeletion->first('password') }}</p>
                                @endif
                            </div>

                            <button type="submit"
                                class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                                Hapus Akun Permanen
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-12 py-8">
        <div
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
            <div class="flex items-center gap-2">
                <span
                    class="w-6 h-6 rounded-lg bg-blue-600 text-white font-bold flex items-center justify-center text-xs">Z</span>
                <span class="font-semibold text-slate-800">E-Commerce</span>
                <span>&copy; {{ date('Y') }} Hak Cipta Dilindungi.</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
                <a href="{{ route('catalog.index') }}" class="hover:text-blue-600 transition">Katalog</a>
                <a href="{{ route('orders.index') }}" class="hover:text-blue-600 transition">Pesanan Saya</a>
            </div>
        </div>
    </footer>

</body>

</html>
