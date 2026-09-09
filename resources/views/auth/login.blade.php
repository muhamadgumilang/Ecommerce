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
                    {{ __('Log in / Sign Up') }}
                </h1>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email Address')" class="text-blue-800 text-sm font-medium" />
                        <x-text-input id="email"
                            class="block mt-1 w-full rounded-lg border-blue-100 bg-blue-50/50 focus:bg-white focus:border-blue-400 focus:ring-blue-400 text-blue-900 placeholder-blue-300"
                            type="email" name="email" :value="old('email')" required autofocus
                            autocomplete="username" placeholder="abc@xyz.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" class="text-blue-800 text-sm font-medium" />
                        <x-text-input id="password"
                            class="block mt-1 w-full rounded-lg border-blue-100 bg-blue-50/50 focus:bg-white focus:border-blue-400 focus:ring-blue-400 text-blue-900"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="••••••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-blue-300 text-blue-500 shadow-sm focus:ring-blue-400"
                                name="remember">
                            <span class="ms-2 text-blue-700">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-blue-500 hover:text-blue-700 hover:underline"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <x-primary-button class="w-full justify-center py-3 rounded-full text-base font-semibold
                            bg-gradient-to-r from-blue-500 to-sky-400 hover:from-blue-600 hover:to-sky-500
                            border-none shadow-lg shadow-blue-200">
                            {{ __('Log in') }}
                        </x-primary-button>
                    </div>
                </form>

                <!-- Social Login -->
                <div class="mt-8 text-center">
                    <p class="text-sm text-blue-400 mb-4">{{ __('or connect with') }}</p>
                    <div class="flex justify-center gap-4">
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full border border-blue-100 hover:bg-blue-50 transition">
                            <svg class="w-5 h-5" fill="#1877F2" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.6 0 0 .6 0 1.325v21.351C0 23.4.6 24 1.325 24H12.82v-9.294H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116C23.4 24 24 23.4 24 22.676V1.325C24 .6 23.4 0 22.675 0z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full border border-blue-100 hover:bg-blue-50 transition">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.66-.22-2.45H12v4.63h6.48c-.28 1.5-1.13 2.77-2.4 3.62v3h3.87c2.27-2.09 3.57-5.17 3.57-8.8z"/>
                                <path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.95-2.93l-3.87-3c-1.08.72-2.45 1.15-4.08 1.15-3.14 0-5.8-2.12-6.75-4.96H1.24v3.1C3.22 21.3 7.28 24 12 24z"/>
                                <path fill="#FBBC05" d="M5.25 14.26A7.2 7.2 0 0 1 4.86 12c0-.78.14-1.55.39-2.26v-3.1H1.24A11.96 11.96 0 0 0 0 12c0 1.93.46 3.76 1.24 5.36l4.01-3.1z"/>
                                <path fill="#EA4335" d="M12 4.77c1.76 0 3.34.6 4.58 1.79l3.44-3.44C17.95 1.19 15.24 0 12 0 7.28 0 3.22 2.7 1.24 6.64l4.01 3.1C6.2 6.9 8.86 4.77 12 4.77z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <p class="text-center text-sm text-blue-500 mt-6">
                    {{ __("Don't have an account?") }}
                    <a href="{{ route('register') }}" class="font-semibold text-blue-700 hover:underline">{{ __('Sign up') }}</a>
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
                        Belanja jadi lebih mudah dan menyenangkan. Temukan ribuan produk pilihan
                        dengan harga terbaik, kualitas terjamin, dan pengiriman cepat ke seluruh Indonesia.
                    </p>
                    <div class="flex items-center gap-3">
                        <button class="px-5 py-2 rounded-full bg-white/90 text-blue-600 text-sm font-semibold hover:bg-white transition">
                            Belanja Sekarang
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
