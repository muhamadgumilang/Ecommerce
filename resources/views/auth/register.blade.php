<x-guest-layout>
    <div class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div
            class="grid w-full max-w-6xl overflow-hidden rounded-[2rem] border border-slate-200 bg-white/80 shadow-[0_28px_80px_rgba(15,23,42,0.12)] backdrop-blur-xl md:grid-cols-[1.02fr_0.98fr]">

            <div class="relative flex flex-col justify-center p-6 sm:p-10 lg:p-12">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-sky-50"></div>
                <div class="relative z-10">
                    <div class="mb-8 flex items-center gap-3">
                        <div
                            class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-sky-500 text-lg font-black text-white shadow-lg shadow-blue-600/25">
                            E
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-blue-500">Store</p>
                            <p class="text-xl font-black tracking-tight text-slate-900">E-Commerce</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <p
                            class="mb-2 inline-flex items-center gap-2 rounded-full border border-blue-200 bg-blue-50 px-3 py-1 text-[10px] font-bold uppercase tracking-[0.18em] text-blue-700">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            Bergabung dengan kami
                        </p>
                        <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                            {{ __('Buat akun baru') }}
                        </h1>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nama Lengkap')"
                                class="mb-2 text-sm font-semibold text-slate-700" />
                            <x-text-input id="name"
                                class="mt-0 block w-full rounded-xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-sm transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-200"
                                type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                                placeholder="Nama kamu" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')"
                                class="mb-2 text-sm font-semibold text-slate-700" />
                            <x-text-input id="email"
                                class="mt-0 block w-full rounded-xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-sm transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-200"
                                type="email" name="email" :value="old('email')" required autocomplete="username"
                                placeholder="abc@xyz.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password')"
                                class="mb-2 text-sm font-semibold text-slate-700" />
                            <x-text-input id="password"
                                class="mt-0 block w-full rounded-xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-sm transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-200"
                                type="password" name="password" required autocomplete="new-password"
                                placeholder="••••••••••••" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')"
                                class="mb-2 text-sm font-semibold text-slate-700" />
                            <x-text-input id="password_confirmation"
                                class="mt-0 block w-full rounded-xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-sm transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-200"
                                type="password" name="password_confirmation" required autocomplete="new-password"
                                placeholder="••••••••••••" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label :value="__('Daftar Sebagai')" class="mb-3 text-sm font-semibold text-slate-700" />
                            <div class="grid gap-3 sm:grid-cols-2">
                                <label
                                    class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 p-3 transition hover:border-blue-200 hover:bg-blue-50">
                                    <input type="radio" name="role" value="Customer"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-400"
                                        {{ old('role', 'Customer') === 'Customer' ? 'checked' : '' }}>
                                    <div>
                                        <span class="block text-sm font-semibold text-slate-800">Pembeli</span>
                                        <span class="block text-xs text-slate-500">Beli produk</span>
                                    </div>
                                </label>
                                <label
                                    class="flex cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50/80 p-3 transition hover:border-blue-200 hover:bg-blue-50">
                                    <input type="radio" name="role" value="Seller"
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-400"
                                        {{ old('role') === 'Seller' ? 'checked' : '' }}>
                                    <div>
                                        <span class="block text-sm font-semibold text-slate-800">Penjual</span>
                                        <span class="block text-xs text-slate-500">Buka toko</span>
                                    </div>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="pt-2">
                            <x-primary-button
                                class="primary-button w-full justify-center rounded-xl border-0 py-3.5 text-base shadow-lg shadow-blue-600/20">
                                {{ __('Daftar') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="mt-8 text-center text-sm text-slate-500">
                        {{ __('Sudah punya akun?') }}
                        <a href="{{ route('login') }}"
                            class="font-bold text-blue-600 transition hover:text-blue-700 hover:underline">
                            {{ __('Masuk di sini') }}
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="relative hidden overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-sky-600 md:block">
                <div class="absolute -left-16 top-12 h-40 w-40 rounded-full border border-white/20 bg-white/5 blur-2xl">
                </div>
                <div class="absolute bottom-0 right-0 h-60 w-60 rounded-full bg-sky-400/20 blur-3xl"></div>
                <div class="absolute right-10 top-16 h-24 w-24 rounded-full border border-white/20"></div>
                <div class="relative z-10 flex h-full flex-col justify-between p-10 text-white lg:p-12">
                    <div>
                        <div
                            class="mb-8 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[10px] font-bold uppercase tracking-[0.18em] text-blue-100">
                            <span class="h-2 w-2 rounded-full bg-sky-300"></span>
                            Buka toko
                        </div>
                        <h2 class="max-w-sm text-4xl font-black leading-tight tracking-tight">
                            Mulai jualan dan raih pelanggan lebih banyak hari ini.
                        </h2>
                    </div>

                    <div class="rounded-3xl border border-white/15 bg-white/10 p-5 backdrop-blur-md">
                        <p class="text-xs uppercase tracking-[0.2em] text-blue-100">Keuntungan</p>
                        <div class="mt-4 space-y-4 text-sm text-blue-50">
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-lg">✓</span>
                                <span>Kelola produk dan stok dengan mudah</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-lg">✓</span>
                                <span>Sistem pesanan lebih teratur dan cepat</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-lg">✓</span>
                                <span>Bangun brand dan loyalitas pelanggan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
