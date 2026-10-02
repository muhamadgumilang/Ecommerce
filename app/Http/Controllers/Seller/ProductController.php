<?php

namespace App\Http\Controllers\Seller;

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
        $sellerId = $request->user()->user_id;

        $products = Product::where('seller_id', $sellerId)
            ->with('category')
            ->latest()
            ->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('owner_id', Auth::id())->get();

        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        abort_unless(Category::where('category_id', $request->integer('category_id'))
            ->where('owner_id', $request->user()->user_id)->exists(), 422, 'Kategori bukan milik Seller ini.');
        $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'product_name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight_gram' => 'required|integer|min:1|max:100000',
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
            'weight_gram' => $request->integer('weight_gram'),
            'description' => $request->input('description'),
            'image' => $imagePaths[0] ?? null,
            'images' => $imagePaths,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan ke toko Anda.');
    }

    public function edit(Product $product)
    {
        // Pastikan hanya pemilik produk yang dapat mengubah
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda bukan pemilik produk ini.');
        }

        $categories = Category::where('owner_id', Auth::id())->get();

        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda bukan pemilik produk ini.');
        }

        abort_unless(Category::where('category_id', $request->integer('category_id'))
            ->where('owner_id', $request->user()->user_id)->exists(), 422, 'Kategori bukan milik Seller ini.');

        $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'product_name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'weight_gram' => 'required|integer|min:1|max:100000',
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
            'weight_gram' => $request->integer('weight_gram'),
            'description' => $request->input('description'),
            'image' => $imagePaths[0] ?? null,
            'images' => $imagePaths,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->seller_id !== Auth::id()) {
            abort(403, 'Akses ditolak. Anda bukan pemilik produk ini.');
        }

        foreach ($product->images ?: array_filter([$product->image]) as $image) {
            Storage::disk('public')->delete($image);
        }

        $product->cartItems()->delete();
        $product->orderDetails()->delete();
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus dari toko Anda.');
    }
}
