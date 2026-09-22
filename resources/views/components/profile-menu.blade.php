<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div x-data="{ open: false }" @click.outside="open = false" class="relative inline-flex">
    <button type="button" @click="open = !open"
        class="inline-flex items-center justify-center rounded-full border border-slate-200 bg-white p-1.5 shadow-sm transition hover:border-blue-200 hover:shadow-md hover:text-blue-600"
        :aria-expanded="open.toString()" aria-haspopup="true" aria-label="Profil pengguna">
        <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-semibold text-white shadow-sm">
            {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
        </span>
        <svg class="ml-1.5 h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" />
        </svg>
    </button>

    <div x-cloak x-show="open" x-transition
        class="absolute right-0 top-full z-50 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white p-2 shadow-xl shadow-slate-900/10"
        role="menu">
        <div class="flex items-center gap-3 border-b border-slate-100 px-2 pb-3 pt-1">
            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-semibold text-white">
                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
            </span>
            <div class="min-w-0">
                <p class="truncate text-xs text-slate-400">Masuk sebagai</p>
                <p class="truncate text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}"
            class="mt-2 block rounded-xl px-3 py-2.5 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-blue-600"
            role="menuitem">
            Profil Saya
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="mt-1 block w-full rounded-xl px-3 py-2.5 text-left text-sm text-rose-600 transition hover:bg-rose-50"
                role="menuitem">
                Keluar dari Akun
            </button>
        </form>
    </div>
</div>
