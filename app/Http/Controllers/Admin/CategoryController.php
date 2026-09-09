<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name), // ✅ Otomatis buat slug (misal: "Sepatu Pria" -> "sepatu-pria")
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->category_id . ',category_id',
        ]);

        // Hapus 'slug' jika memang tabel categories kamu tidak memiliki kolom slug
        $category->update([
            'name' => $request->name,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        try {
            // Loop semua produk di kategori ini, hapus data turunannya lalu hapus produknya
            foreach ($category->products as $product) {
                $product->cartItems()->delete();
                $product->orderDetails()->delete();
                $product->delete();
            }

            // Setelah bersih, hapus kategorinya
            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori beserta produk terkait berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
}
