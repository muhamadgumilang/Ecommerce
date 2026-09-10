<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View; // Tambahkan namespace View

class CatalogController extends Controller
{
    // Ubah return type menjadi View atau gabungkan jika masih dipakai untuk API
    public function index(): View
    {
        // Mengambil data dari tabel products
        $products = Product::query()
            ->latest()
            ->paginate(12);

        return view('catalog.index', compact('products'));
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
