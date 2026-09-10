<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-slate-800">
                    {{ __('Produk Toko Saya') }}
                </h2>
                <p class="text-sm text-slate-500 mt-1">
                    Kelola katalog barang dagangan yang dijual oleh toko Anda.
                </p>
            </div>
            <a href="{{ route('seller.products.create') }}" class="px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-2 self-start sm:self-auto">
                <span>+</span> {{ __('Tambah Produk Baru') }}
            </a>
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
                                <th class="py-3.5 px-6">No</th>
                                <th class="py-3.5 px-6">Foto</th>
                                <th class="py-3.5 px-6">Nama Produk</th>
                                <th class="py-3.5 px-6">Kategori</th>
                                <th class="py-3.5 px-6">Harga</th>
                                <th class="py-3.5 px-6">Stok</th>
                                <th class="py-3.5 px-6 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse ($products as $product)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="py-4 px-6 text-slate-400 text-xs">
                                        {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="w-14 h-14 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 flex items-center justify-center">
                                            @if (!empty($product->image))
                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover">
                                            @else
                                                <span class="text-[10px] text-slate-400 font-medium">No Image</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-semibold text-slate-800">{{ $product->product_name }}</div>
                                        <div class="text-xs text-slate-400 line-clamp-1 max-w-xs">{{ $product->description }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full bg-slate-100 text-slate-600">
                                            {{ $product->category?->name ?? 'Tanpa Kategori' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-slate-800">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($product->stock > 5)
                                            <span class="text-emerald-600 font-semibold">{{ $product->stock }}</span>
                                        @elseif ($product->stock > 0)
                                            <span class="text-amber-600 font-semibold">{{ $product->stock }} (Menipis)</span>
                                        @else
                                            <span class="text-rose-600 font-semibold">Habis</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-center space-x-2">
                                        <a href="{{ route('seller.products.edit', $product) }}" class="px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-xs font-semibold transition inline-block">
                                            Edit
                                        </a>
                                        <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini dari toko?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-xs font-semibold transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-12 text-center text-slate-400 text-sm">
                                        <div class="text-3xl mb-2">📦</div>
                                        <p class="font-medium text-slate-600">Belum ada produk di toko Anda</p>
                                        <p class="text-xs text-slate-400 mt-1">Mulai jualan dengan menambahkan produk pertama Anda.</p>
                                        <a href="{{ route('seller.products.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-semibold hover:bg-blue-700 transition">
                                            + Tambah Produk Sekarang
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($products->hasPages())
                    <div class="p-6 border-t border-slate-100">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
