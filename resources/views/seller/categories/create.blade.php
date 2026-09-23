<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-2xl text-slate-800">Tambah Kategori Seller</h2></x-slot>
    <div class="py-8"><div class="mx-auto max-w-2xl sm:px-6 lg:px-8"><div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
        <form action="{{ route('seller.categories.store') }}" method="POST" class="space-y-5">
            @csrf
            <div><label for="name" class="mb-1 block text-sm font-semibold text-slate-700">Nama Kategori</label><input id="name" name="name" value="{{ old('name') }}" required class="w-full rounded-xl border-slate-200"><p class="mt-1 text-xs text-slate-400">Kategori ini hanya terlihat dan digunakan oleh toko Anda.</p>@error('name')<p class="text-xs text-rose-500">{{ $message }}</p>@enderror</div>
            <div class="flex justify-end gap-3"><a href="{{ route('seller.categories.index') }}" class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600">Batal</a><button class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white">Simpan Kategori</button></div>
        </form>
    </div></div></div>
</x-app-layout>
