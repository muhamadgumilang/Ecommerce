<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-slate-800">Edit Produk: {{ $product->product_name }}</h2><a
                href="{{ route('admin.products.index') }}"
                class="px-4 py-2 bg-slate-200 text-slate-700 rounded-xl text-sm">&larr; Kembali</a>
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-2xl border border-slate-100 p-6">@include('admin.products._form', [
                'action' => route('admin.products.update', $product),
                'method' => 'PUT',
                'submitLabel' => 'Perbarui Produk',
            ])</div>
        </div>
    </div>
</x-app-layout>
