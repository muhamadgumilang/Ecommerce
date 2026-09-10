<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800">
                    {{ __('Pesanan Masuk Toko') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Daftar pesanan dari pembeli yang memesan produk dari toko Anda.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl text-sm flex items-center gap-3">
                    <span class="text-emerald-500 text-lg">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[700px]">
                        <thead class="bg-slate-50 text-slate-400 text-xs uppercase font-semibold">
                            <tr>
                                <th class="py-3.5 px-6">ID Pesanan</th>
                                <th class="py-3.5 px-6">Pembeli</th>
                                <th class="py-3.5 px-6">Tanggal</th>
                                <th class="py-3.5 px-6">Item Toko Anda</th>
                                <th class="py-3.5 px-6">Status Pesanan</th>
                                <th class="py-3.5 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-4 px-6 font-semibold text-slate-800">
                                        #{{ $order->order_id }}
                                    </td>
                                    <td class="py-4 px-6 text-slate-700">
                                        <div class="font-medium">{{ $order->customer?->name ?? 'Customer' }}</div>
                                        <div class="text-xs text-slate-400">{{ $order->customer?->phone ?? $order->customer?->email }}</div>
                                    </td>
                                    <td class="py-4 px-6 text-slate-500 text-xs">
                                        {{ $order->order_date ? $order->order_date->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="space-y-1">
                                            @foreach ($order->orderDetails as $detail)
                                                <div class="text-xs text-slate-700 flex items-center gap-2">
                                                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                                    <span class="font-medium">{{ $detail->product?->product_name }}</span>
                                                    <span class="text-slate-400 font-semibold">× {{ $detail->quantity }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @php
                                            $stClass = match($order->order_status) {
                                                'Completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'Shipped' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'Processing' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'Cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                default => 'bg-slate-50 text-slate-600 border-slate-200',
                                            };
                                        @endphp
                                        <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-full border {{ $stClass }}">
                                            {{ $order->order_status }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('seller.orders.show', $order) }}" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition inline-block shadow-sm">
                                            Detail & Proses
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center text-slate-400 text-sm">
                                        <div class="text-3xl mb-2">🚚</div>
                                        <p class="font-medium text-slate-600">Belum ada pesanan masuk</p>
                                        <p class="text-xs text-slate-400 mt-1">Pesanan dari pembeli untuk produk toko Anda akan tampil di sini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($orders->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
