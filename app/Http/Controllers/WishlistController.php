<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlists = Wishlist::where('user_id', $request->user()->user_id)
            ->with(['product.category', 'product.seller'])
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function store(Request $request, Product $product)
    {
        Wishlist::firstOrCreate([
            'user_id' => $request->user()->user_id,
            'product_id' => $product->product_id,
        ]);

        return back()->with('success', 'Produk ditambahkan ke wishlist.');
    }

    public function destroy(Request $request, Product $product)
    {
        Wishlist::where('user_id', $request->user()->user_id)
            ->where('product_id', $product->product_id)
            ->delete();

        return back()->with('success', 'Produk dihapus dari wishlist.');
    }
}
