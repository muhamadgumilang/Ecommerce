@auth
    @php($wishlistCount = Auth::user()->wishlists()->count())
    <a href="{{ route('wishlist.index') }}"
        class="group inline-flex items-center gap-2 rounded-full border border-rose-100 bg-gradient-to-r from-rose-50 to-pink-50 px-2.5 py-1.5 text-sm font-semibold text-rose-700 shadow-sm shadow-rose-100 transition hover:-translate-y-0.5 hover:border-rose-200 hover:from-rose-100 hover:to-pink-100 hover:shadow-md"
        title="Wishlist Saya">
        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-base leading-none text-rose-500 shadow-sm transition group-hover:scale-110">♥</span>
        <span class="hidden sm:inline">Wishlist</span>
        @if ($wishlistCount > 0)
            <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-rose-500 px-1 text-[10px] font-bold text-white">
                {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
            </span>
        @endif
    </a>
@endauth
