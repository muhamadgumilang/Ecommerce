<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800">{{ __('Manajemen Produk') }}</h2>
            <a href="{{ route('admin.products.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-medium hover:bg-blue-700">+ Tambah
                Produk</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm">
                    {{ session('success') }}</div>
            @endif
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                <div class="p-6 text-slate-900 overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-xs uppercase tracking-wider">
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">Foto</th>
                                <th class="py-3 px-4">Produk</th>
                                <th class="py-3 px-4">Kategori</th>
                                <th class="py-3 px-4">Harga</th>
                                <th class="py-3 px-4">Stok</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($products as $product)
                                <tr>
                                    <td class="py-3 px-4 text-slate-500">
                                        {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                    </td>
                                    <!-- Kolom Foto Thumbnail -->
                                    <!-- Kolom Foto Thumbnail -->
                                    <td class="py-3 px-4">
                                        <div style="width: 56px; height: 56px; min-width: 56px; min-height: 56px;" class="rounded-xl bg-slate-100 overflow-hidden border border-slate-200 flex items-center justify-center">
                                            @if(!empty($product->image))
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" style="width: 56px; height: 56px; object-fit: cover;">
                                            @else
                                                <span class="text-[10px] text-slate-400 font-medium">No Image</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 font-medium text-slate-800">{{ $product->product_name }}</td>
                                    <td class="py-3 px-4 text-slate-500">{{ $product->category?->name ?? '-' }}</td>
                                    <td class="py-3 px-4 text-slate-600">Rp
                                        {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-slate-600">{{ $product->stock }}</td>
                                    <td class="py-3 px-4 text-center space-x-2">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="text-amber-600 hover:underline font-medium">Edit</a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline font-medium">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-6 text-slate-400">Belum ada data produk.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
