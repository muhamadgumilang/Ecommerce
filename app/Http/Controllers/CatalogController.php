<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Catalog;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View; // Tambahkan namespace View

class CatalogController extends Controller
{
    // Ubah return type menjadi View atau gabungkan jika masih dipakai untuk API
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $categoryFilter = trim((string) $request->query('category', ''));
        $categoryIds = collect(explode(',', $categoryFilter))
            ->filter(fn ($id) => ctype_digit($id) && (int) $id > 0)
            ->map(fn ($id) => (int) $id)
            ->values();
        $minPrice = is_numeric($request->query('min_price')) ? max(0, (float) $request->query('min_price')) : null;
        $maxPrice = is_numeric($request->query('max_price')) ? max(0, (float) $request->query('max_price')) : null;

        $products = Product::query()
            ->with(['category', 'seller'])
            ->when($categoryIds->isNotEmpty(), fn ($query) => $query->whereIn('category_id', $categoryIds))
            ->when($minPrice !== null, fn ($query) => $query->where('price', '>=', $minPrice))
            ->when($maxPrice !== null, fn ($query) => $query->where('price', '<=', $maxPrice))
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('product_name', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get()
            ->groupBy(fn ($category) => preg_replace('/\s+(Admin|Seller)$/i', '', $category->name))
            ->map(fn ($group, $name) => (object) [
                'name' => $name,
                'ids' => $group->pluck('category_id')->implode(','),
            ])
            ->values();

        return view('catalog.index', compact('products', 'search', 'categories', 'categoryFilter', 'minPrice', 'maxPrice'));
    }

    // Method lainnya tetap sama...
    public function show(Catalog $catalog): JsonResponse
    {
        abort_unless($catalog->is_active, 404);

        return response()->json($catalog);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:150', 'unique:catalogs,slug'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $catalog = Catalog::create($data);

        return response()->json($catalog, 201);
    }

    public function update(Request $request, Catalog $catalog): JsonResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:150'],
            'slug' => ['sometimes', 'string', 'max:150', 'unique:catalogs,slug,'.$catalog->catalog_id.',catalog_id'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $catalog->update($data);

        return response()->json($catalog->fresh());
    }

    public function destroy(Catalog $catalog): JsonResponse
    {
        $catalog->delete();

        return response()->json(null, 204);
    }
}
