<?php

namespace App\Http\Controllers;

<<<<<<< Updated upstream
use App\Models\Product;
use App\Models\Category;
=======
>>>>>>> Stashed changes
use Illuminate\Http\Request;

class ProductController extends Controller
{
<<<<<<< Updated upstream
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filter berdasarkan kategori jika dipilih di Blade
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->get();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with(['category', 'seller'])->findOrFail($id);
        return view('products.show', compact('product'));
    }
}
=======
    //
}
>>>>>>> Stashed changes
