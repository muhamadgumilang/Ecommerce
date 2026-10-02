<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('seller_id', $request->user()->user_id)
            ->with(['category', 'seller'])
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('owner_id', Auth::id())->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        abort_unless(Category::where('category_id', $request->integer('category_id'))
            ->where('owner_id', $request->user()->user_id)->exists(), 422, 'Kategori bukan milik Admin ini.');
        $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'product_name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'images' => 'nullable|array|max:8',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePaths = collect($request->file('images', []))
            ->map(fn ($image) => $image->store('products', 'public'))
            ->all();

        Product::create([
            'seller_id' => $request->user()->user_id,
            'category_id' => $request->integer('category_id'),
            'product_name' => $request->product_name,
            'price' => $request->input('price'),
            'stock' => $request->integer('stock'),
            'description' => $request->input('description'),
            'image' => $imagePaths[0] ?? null,
            'images' => $imagePaths,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        abort_unless($product->seller_id === Auth::id(), 403);
        $categories = Category::where('owner_id', Auth::id())->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        abort_unless($product->seller_id === $request->user()->user_id, 403);
        abort_unless(Category::where('category_id', $request->integer('category_id'))
            ->where('owner_id', $request->user()->user_id)->exists(), 422, 'Kategori bukan milik Admin ini.');
        $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'product_name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'images' => 'nullable|array|max:8',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'string',
        ]);

        $imagePaths = $product->images ?: array_values(array_filter([$product->image]));
        $removedImages = array_intersect($imagePaths, $request->input('remove_images', []));
        $imagePaths = array_values(array_diff($imagePaths, $removedImages));
        $newImages = $request->file('images', []);

        if (count($imagePaths) + count($newImages) > 8) {
            throw ValidationException::withMessages(['images' => 'Maksimal 8 foto untuk setiap produk.']);
        }

        foreach ($removedImages as $image) {
            Storage::disk('public')->delete($image);
        }

        $imagePaths = array_merge($imagePaths, collect($newImages)
            ->map(fn ($image) => $image->store('products', 'public'))
            ->all());

        $product->update([
            'category_id' => $request->integer('category_id'),
            'product_name' => $request->product_name,
            'price' => $request->input('price'),
            'stock' => $request->integer('stock'),
            'description' => $request->input('description'),
            'image' => $imagePaths[0] ?? null,
            'images' => $imagePaths,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        abort_unless($product->seller_id === Auth::id(), 403);
        // Hapus file gambar dari storage saat produk dihapus
        foreach ($product->images ?: array_filter([$product->image]) as $image) {
            Storage::disk('public')->delete($image);
        }

        $product->cartItems()->delete();
        $product->orderDetails()->delete();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
