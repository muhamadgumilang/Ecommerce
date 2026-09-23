<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('owner_id', $request->user()->user_id)
            ->latest()
            ->paginate(10);

        return view('seller.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('seller.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create([
            'owner_id' => $request->user()->user_id,
            'name' => $data['name'],
            'slug' => Str::slug($data['name']) . '-' . $request->user()->user_id,
        ]);

        return redirect()->route('seller.categories.index')->with('success', 'Kategori seller berhasil ditambahkan.');
    }

    public function edit(Request $request, Category $category)
    {
        abort_unless($category->owner_id === $request->user()->user_id, 403);

        return view('seller.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        abort_unless($category->owner_id === $request->user()->user_id, 403);

        $data = $request->validate(['name' => 'required|string|max:255']);
        $category->update(['name' => $data['name'], 'slug' => Str::slug($data['name']) . '-' . $category->owner_id]);

        return redirect()->route('seller.categories.index')->with('success', 'Kategori seller berhasil diperbarui.');
    }

    public function destroy(Request $request, Category $category)
    {
        abort_unless($category->owner_id === $request->user()->user_id, 403);
        foreach ($category->products as $product) {
            $product->cartItems()->delete();
            $product->orderDetails()->delete();
            $product->delete();
        }
        $category->delete();

        return redirect()->route('seller.categories.index')->with('success', 'Kategori seller berhasil dihapus.');
    }
}
