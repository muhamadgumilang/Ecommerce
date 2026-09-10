<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800">{{ __('Manajemen Pesanan') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">{{ session('success') }}</div>
            @endif
            <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6 overflow-x-auto">
                <table class="w-full text-left min-w-[700px]">
                    <thead class="border-b border-slate-100 text-slate-400 text-xs uppercase">
                        <tr><th class="py-3 px-4">Pesanan</th><th class="py-3 px-4">Pelanggan</th><th class="py-3 px-4">Tanggal</th><th class="py-3 px-4">Total</th><th class="py-3 px-4">Status</th><th class="py-3 px-4">Aksi</th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($orders as $order)
                            <tr>
                                <td class="py-3 px-4 font-medium">#{{ $order->order_id }}</td>
                                <td class="py-3 px-4">{{ $order->customer?->name ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $order->order_date?->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-4">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">{{ $order->order_status }}</td>
                                <td class="py-3 px-4 space-x-2"><a class="text-blue-600 hover:underline" href="{{ route('admin.orders.show', $order) }}">Detail</a><a class="text-amber-600 hover:underline" href="{{ route('admin.orders.edit', $order) }}">Edit</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-6 text-center text-slate-400">Belum ada pesanan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
