@auth
    @php($cartQuantity = Auth::user()->cart?->cartItems()->sum('quantity') ?? 0)
@else
    @php($cartQuantity = 0)
@endauth

<a href="{{ route('cart.index') }}"
    class="relative inline-flex h-10 w-10 items-center justify-center rounded-xl border {{ request()->routeIs('cart.index') ? 'border-blue-200 bg-blue-50 text-blue-600' : 'border-slate-200 bg-white text-slate-500 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600' }} transition"
    title="Keranjang belanja" aria-label="Keranjang belanja">
    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
            d="M3 4h2l1.6 9.2a2 2 0 0 0 2 1.8h7.8a2 2 0 0 0 1.9-1.4L20 7H6.2M10 19a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm8 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" />
    </svg>
    <span class="sr-only">Keranjang belanja</span>
    @if ($cartQuantity > 0)
        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-blue-600 px-1 text-[10px] font-bold text-white shadow-sm">
            {{ $cartQuantity > 99 ? '99+' : $cartQuantity }}
        </span>
    @endif
</a>
