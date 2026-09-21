<style>
    [x-cloak] {
        display: none !important;
    }
</style>

<div x-data="{ open: false }" @click.outside="open = false" class="relative inline-flex">
    <button type="button" @click="open = !open"
        class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-600"
        :aria-expanded="open.toString()" aria-haspopup="true">
        <span>Profil</span>
        <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6" />
        </svg>
    </button>

    <div x-cloak x-show="open" x-transition
        class="absolute right-0 top-full z-50 mt-2 w-52 overflow-hidden rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg shadow-slate-900/10"
        role="menu">
        <div class="border-b border-slate-100 px-3 py-2">
            <p class="truncate text-xs text-slate-400">Masuk sebagai</p>
            <p class="truncate text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</p>
        </div>
        <a href="{{ route('profile.edit') }}"
            class="block rounded-lg px-3 py-2.5 text-sm text-slate-600 transition hover:bg-slate-50 hover:text-blue-600"
            role="menuitem">
            Profil Saya
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="block w-full rounded-lg px-3 py-2.5 text-left text-sm text-rose-600 transition hover:bg-rose-50"
                role="menuitem">
                Keluar dari Akun
            </button>
        </form>
    </div>
</div>
