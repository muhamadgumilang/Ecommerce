<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $categories = Category::all();
        return view('seller.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'product_name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'seller_id' => $request->user()->user_id,
            'category_id' => $request->integer('category_id'),
            'product_name' => $request->product_name,
            'price' => $request->input('price'),
            'stock' => $request->integer('stock'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil ditambahkan ke toko Anda.');
    }

    public function edit(Product $product)
    {
        // Pastikan hanya pemilik produk yang dapat mengubah
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Akses ditolak. Anda bukan pemilik produk ini.');
        }

        $categories = Category::all();
        return view('seller.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Akses ditolak. Anda bukan pemilik produk ini.');
        }

        $request->validate([
            'category_id' => 'required|exists:categories,category_id',
            'product_name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->integer('category_id'),
            'product_name' => $request->product_name,
            'price' => $request->input('price'),
            'stock' => $request->integer('stock'),
            'description' => $request->input('description'),
            'image' => $imagePath,
        ]);

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->seller_id !== auth()->id()) {
            abort(403, 'Akses ditolak. Anda bukan pemilik produk ini.');
        }

        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->cartItems()->delete();
        $product->orderDetails()->delete();
        $product->delete();

        return redirect()->route('seller.products.index')->with('success', 'Produk berhasil dihapus dari toko Anda.');
    }
}
