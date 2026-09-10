<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center"><h2 class="font-semibold text-xl text-slate-800">Pesanan #{{ $order->order_id }}</h2><a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline">Kembali</a></div>
    </x-slot>

    <div class="py-6"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
        <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6">
            <p><strong>Pelanggan:</strong> {{ $order->customer?->name ?? '-' }}</p>
            <p><strong>Status:</strong> {{ $order->order_status }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
            <p><strong>Alamat:</strong> {{ $order->checkout?->shipping_address ?? '-' }}</p>
            <p><strong>Kurir:</strong> {{ $order->checkout?->courier ?? '-' }}</p>
        </div>
        <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6">
            <h3 class="font-semibold mb-3">Detail Produk</h3>
            @forelse ($order->orderDetails as $detail)
                <div class="flex justify-between border-b border-slate-100 py-2"><span>{{ $detail->product?->product_name ?? 'Produk' }} x {{ $detail->quantity }}</span><span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span></div>
            @empty
                <p class="text-slate-400">Tidak ada detail produk.</p>
            @endforelse
        </div>
    </div></div>
</x-app-layout>
