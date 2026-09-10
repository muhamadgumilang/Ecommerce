<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center"><h2 class="font-semibold text-xl text-slate-800">Edit Status Pesanan #{{ $order->order_id }}</h2><a href="{{ route('admin.orders.index') }}" class="text-blue-600 hover:underline">Kembali</a></div>
    </x-slot>

    <div class="py-6"><div class="max-w-2xl mx-auto sm:px-6 lg:px-8"><div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6">
        <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="space-y-4">
            @csrf @method('PUT')
            <div><label for="order_status" class="block text-sm font-medium text-slate-700">Status Pesanan</label><select id="order_status" name="order_status" class="mt-1 block w-full rounded-lg border-slate-300" required>
                @foreach (['Pending Payment', 'Processing', 'Shipped', 'Completed', 'Cancelled'] as $status)
                    <option value="{{ $status }}" @selected($order->order_status === $status)>{{ $status }}</option>
                @endforeach
            </select></div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Simpan</button>
        </form>
    </div></div></div>
</x-app-layout>
