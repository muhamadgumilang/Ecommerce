<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-slate-800">Kategori Toko Saya</h2>
            <a href="{{ route('seller.categories.create') }}" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">
                + Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">{{ session('success') }}</div>
            @endif

            <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="overflow-x-auto p-6">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-100 text-xs uppercase tracking-wider text-slate-400">
                            <tr><th class="py-3 px-4">No</th><th class="py-3 px-4">Nama Kategori</th><th class="py-3 px-4">Slug</th><th class="py-3 px-4 text-center">Aksi</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($categories as $category)
                                <tr>
                                    <td class="py-3 px-4 text-slate-500">{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                    <td class="py-3 px-4 font-semibold text-slate-800">{{ $category->name }}</td>
                                    <td class="py-3 px-4 text-slate-500">{{ $category->slug }}</td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('seller.categories.edit', $category) }}" class="mr-2 font-semibold text-amber-600 hover:underline">Edit</a>
                                        <form action="{{ route('seller.categories.destroy', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini beserta produknya?')">
                                            @csrf @method('DELETE')
                                            <button class="font-semibold text-rose-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="py-10 text-center text-slate-400">Belum ada kategori toko.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $categories->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
