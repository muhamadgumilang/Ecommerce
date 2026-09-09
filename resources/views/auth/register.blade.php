<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-blue-50 px-4 py-8">
        <div class="w-full max-w-4xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden grid md:grid-cols-2 relative">

            <!-- KIRI: FORM -->
            <div class="p-8 sm:p-12 flex flex-col justify-center relative z-10">

                <!-- Logo -->
                <div class="mb-6">
                    <div class="w-10 h-10 rounded-full border-4 border-blue-500"></div>
                </div>

                <h1 class="text-xl font-semibold text-blue-900 mb-8">
                    {{ __('Buat Akun Baru') }}
                </h1>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap')" class="text-blue-800 text-sm font-medium" />
                        <x-text-input id="name"
                            class="block mt-1 w-full rounded-lg border-blue-100 bg-blue-50/50 focus:bg-white focus:border-blue-400 focus:ring-blue-400 text-blue-900 placeholder-blue-300"
                            type="text" name="name" :value="old('name')" required autofocus
                            autocomplete="name" placeholder="Nama kamu" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" class="text-blue-800 text-sm font-medium" />
                        <x-text-input id="email"
                            class="block mt-1 w-full rounded-lg border-blue-100 bg-blue-50/50 focus:bg-white focus:border-blue-400 focus:ring-blue-400 text-blue-900 placeholder-blue-300"
                            type="email" name="email" :value="old('email')" required
                            autocomplete="username" placeholder="abc@xyz.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-blue-800 text-sm font-medium" />
                        <x-text-input id="password"
                            class="block mt-1 w-full rounded-lg border-blue-100 bg-blue-50/50 focus:bg-white focus:border-blue-400 focus:ring-blue-400 text-blue-900"
                            type="password" name="password" required autocomplete="new-password"
                            placeholder="••••••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-blue-800 text-sm font-medium" />
                        <x-text-input id="password_confirmation"
                            class="block mt-1 w-full rounded-lg border-blue-100 bg-blue-50/50 focus:bg-white focus:border-blue-400 focus:ring-blue-400 text-blue-900"
                            type="password" name="password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••••••" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center py-3 rounded-full text-base font-semibold
                            bg-gradient-to-r from-blue-500 to-sky-400 hover:from-blue-600 hover:to-sky-500
                            border-none shadow-lg shadow-blue-200">
                            {{ __('Daftar') }}
                        </x-primary-button>
                    </div>
                </form>

                <p class="text-center text-sm text-blue-500 mt-6">
                    {{ __('Sudah punya akun?') }}
                    <a href="{{ route('login') }}" class="font-semibold text-blue-700 hover:underline">{{ __('Masuk di sini') }}</a>
                </p>
            </div>

            <!-- KANAN: PANEL DEKORATIF -->
            <div class="hidden md:block relative bg-gradient-to-br from-blue-400 via-sky-400 to-blue-200 overflow-hidden">
                <!-- Decorative circles -->
                <div class="absolute -top-10 -left-10 w-40 h-40 rounded-full border border-white/30"></div>
                <div class="absolute bottom-10 left-10 w-24 h-24 rounded-full border border-white/20"></div>
                <div class="absolute top-1/3 -right-6 w-16 h-16 rounded-full bg-white/10"></div>

                <div class="relative z-10 h-full flex flex-col justify-center px-10 text-white">
                    <h2 class="text-4xl font-bold mb-4">Project Kami</h2>
                    <p class="text-sm text-blue-50/90 leading-relaxed mb-6 max-w-xs">
                        Gabung sekarang dan nikmati kemudahan belanja online.
                        Ribuan produk pilihan, promo eksklusif, dan pengiriman cepat menanti kamu.
                    </p>
                    <div class="flex items-center gap-3">
                        <button class="px-5 py-2 rounded-full bg-white/90 text-blue-600 text-sm font-semibold hover:bg-white transition">
                            Mulai Belanja
                        </button>
                        <button class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center hover:bg-white/30 transition">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
