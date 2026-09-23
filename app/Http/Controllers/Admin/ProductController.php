<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Jangan lupa import Storage

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi foto
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            // Simpan file ke folder 'products' di disk public
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'seller_id' => $request->user()->user_id,
            'category_id' => $request->integer('category_id'),
            'product_name' => $request->product_name,
            'price' => $request->input('price'),
            'stock' => $request->integer('stock'),
            'description' => $request->input('description'),
            'image' => $imagePath, // Simpan path gambar ke database
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // Validasi foto
        ]);

        $imagePath = $product->image; // Pertahankan gambar lama secara default

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            // Simpan gambar baru
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'category_id' => $request->integer('category_id'),
            'product_name' => $request->product_name,
            'price' => $request->input('price'),
            'stock' => $request->integer('stock'),
            'description' => $request->input('description'),
            'image' => $imagePath, // Perbarui path gambar di database
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        abort_unless($product->seller_id === Auth::id(), 403);
        // Hapus file gambar dari storage saat produk dihapus
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->cartItems()->delete();
        $product->orderDetails()->delete();
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
