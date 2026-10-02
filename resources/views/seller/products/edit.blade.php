<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('seller.products.index') }}" class="text-slate-400 hover:text-slate-600 transition text-sm">
                &larr; Kembali
            </a>
            <h2 class="font-bold text-2xl text-slate-800">
                {{ __('Edit Produk: ') }} {{ $product->product_name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-8">

                <form action="{{ route('seller.products.update', $product) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Nama Produk -->
                    <div>
                        <label for="product_name" class="block text-sm font-semibold text-slate-700 mb-1">
                            Nama Produk <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="product_name" id="product_name"
                            value="{{ old('product_name', $product->product_name) }}" required
                            class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5">
                        @error('product_name')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori & Harga -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1">
                                Kategori <span class="text-rose-500">*</span>
                            </label>
                            <select name="category_id" id="category_id" required
                                class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->category_id }}"
                                        {{ old('category_id', $product->category_id) == $cat->category_id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-semibold text-slate-700 mb-1">
                                Harga (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="price" id="price"
                                value="{{ old('price', (int) $product->price) }}" required min="0" step="1000"
                                class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5">
                            @error('price')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Stok, Berat & Foto Produk -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="stock" class="block text-sm font-semibold text-slate-700 mb-1">
                                Jumlah Stok <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="stock" id="stock"
                                value="{{ old('stock', $product->stock) }}" required min="0"
                                class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5">
                            @error('stock')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="weight_gram" class="block text-sm font-semibold text-slate-700 mb-1">
                                Berat Produk (gram) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="weight_gram" id="weight_gram"
                                value="{{ old('weight_gram', $product->weight_gram ?? 1000) }}" required min="1"
                                class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5"
                                placeholder="Contoh: 750">
                            @error('weight_gram')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="images" class="block text-sm font-semibold text-slate-700 mb-1">
                                Tambah Foto Produk
                            </label>
                            <input type="file" name="images[]" id="images" accept="image/*" multiple
                                class="w-full rounded-xl border-slate-200 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100">
                            <span class="text-xs text-slate-400 mt-1 block">Pilih hingga 8 foto, masing-masing maksimal
                                2MB.</span>
                            @error('images')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror
                            @error('images.*')
                                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                            @enderror

                            @php($productImages = $product->images ?: array_values(array_filter([$product->image])))
                            @if ($productImages)
                                <div class="mt-3 flex flex-wrap gap-3">
                                    @foreach ($productImages as $image)
                                        <label class="text-xs text-slate-600">
                                            <img src="{{ asset('storage/' . $image) }}"
                                                alt="Foto {{ $product->product_name }}"
                                                class="h-16 w-16 rounded-lg border border-slate-200 object-cover">
                                            <span class="mt-1 flex items-center gap-1">
                                                <input type="checkbox" name="remove_images[]"
                                                    value="{{ $image }}">
                                                Hapus
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Deskripsi Produk -->
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-700 mb-1">
                            Deskripsi Produk
                        </label>
                        <textarea name="description" id="description" rows="4"
                            class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 px-3.5">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('seller.products.index') }}"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-sm transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
