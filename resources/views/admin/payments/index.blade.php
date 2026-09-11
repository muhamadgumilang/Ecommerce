<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="font-semibold text-xl text-slate-800">Manajemen Pembayaran</h2>
                <p class="text-sm text-slate-500 mt-1">Pantau dan ubah status pembayaran seluruh pesanan.</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-blue-600 hover:underline">Lihat pesanan</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
                    {{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm">
                    {{ $errors->first() }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6 overflow-x-auto">
                <table class="w-full text-left min-w-[900px]">
                    <thead class="border-b border-slate-100 text-slate-400 text-xs uppercase">
                        <tr>
                            <th class="py-3 px-4">Pesanan</th>
                            <th class="py-3 px-4">Pelanggan</th>
                            <th class="py-3 px-4">Metode</th>
                            <th class="py-3 px-4">Transaksi</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Diubah Oleh</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($payments as $payment)
                            @php
                                $statusStyles = [
                                    'Verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    'Pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Failed' => 'bg-rose-50 text-rose-700 border-rose-200',
                                ];
                            @endphp
                            <tr>
                                <td class="py-3 px-4">
                                    <a class="font-semibold text-blue-600 hover:underline"
                                        href="{{ route('admin.orders.show', $payment->order) }}">#{{ $payment->order_id }}</a>
                                    <div class="text-xs text-slate-500 mt-1">Rp
                                        {{ number_format($payment->order?->total_amount ?? 0, 0, ',', '.') }}</div>
                                </td>
                                <td class="py-3 px-4">{{ $payment->order?->customer?->name ?? '-' }}</td>
                                <td class="py-3 px-4">{{ $payment->payment_method }}</td>
                                <td class="py-3 px-4 text-xs text-slate-500">{{ $payment->transaction_id ?? '-' }}</td>
                                <td class="py-3 px-4">
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold border {{ $statusStyles[$payment->payment_status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">{{ $payment->payment_status }}</span>
                                </td>
                                <td class="py-3 px-4">{{ $payment->admin?->name ?? 'Otomatis / belum ada' }}</td>
                                <td class="py-3 px-4">
                                    <form method="POST" action="{{ route('admin.payments.update', $payment) }}"
                                        class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="payment_status"
                                            class="text-xs rounded-lg border-slate-200 focus:border-blue-500 focus:ring-blue-500 py-1.5">
                                            @foreach (['Pending', 'Verified', 'Failed'] as $status)
                                                <option value="{{ $status }}" @selected($payment->payment_status === $status)>
                                                    {{ $status }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                            class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-xs font-semibold hover:bg-blue-700">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">Belum ada data pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $payments->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
