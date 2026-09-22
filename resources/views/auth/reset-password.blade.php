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
                            Keamanan akun
                        </p>
                        <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                            {{ __('Buat password baru') }}
                        </h1>
                    </div>

                    <p class="mb-6 text-sm leading-6 text-slate-600">
                        {{ __('Masukkan password baru Anda untuk mengaktifkan kembali akun.') }}
                    </p>

                    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div>
                            <x-input-label for="email" :value="__('Email')"
                                class="mb-2 text-sm font-semibold text-slate-700" />
                            <x-text-input id="email"
                                class="mt-0 block w-full rounded-xl border-slate-200 bg-slate-50/80 px-4 py-3 text-slate-900 shadow-sm transition focus:border-blue-400 focus:bg-white focus:ring-2 focus:ring-blue-200"
                                type="email" name="email" :value="old('email', $request->email)" required autofocus
                                autocomplete="username" placeholder="abc@xyz.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" :value="__('Password Baru')"
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

                        <div class="pt-2">
                            <x-primary-button
                                class="primary-button w-full justify-center rounded-xl border-0 py-3.5 text-base shadow-lg shadow-blue-600/20">
                                {{ __('Reset Password') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <div class="mt-8 text-center text-sm text-slate-500">
                        {{ __('Sudah ingat password?') }}
                        <a href="{{ route('login') }}"
                            class="font-bold text-blue-600 transition hover:text-blue-700 hover:underline">
                            {{ __('Kembali ke login') }}
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
                            Keamanan
                        </div>
                        <h2 class="max-w-sm text-4xl font-black leading-tight tracking-tight">
                            Password baru yang kuat menjaga akun Anda tetap aman.
                        </h2>
                    </div>

                    <div class="rounded-3xl border border-white/15 bg-white/10 p-5 backdrop-blur-md">
                        <p class="text-xs uppercase tracking-[0.2em] text-blue-100">Tips aman</p>
                        <div class="mt-4 space-y-4 text-sm text-blue-50">
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-lg">✓</span>
                                <span>Gunakan kombinasi huruf, angka, dan simbol</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-lg">✓</span>
                                <span>Jangan gunakan password yang sama seperti akun lain</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white/15 text-lg">✓</span>
                                <span>Pastikan password mudah diingat namun sulit ditebak</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
