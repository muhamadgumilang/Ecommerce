<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Pastikan Model Product sudah ada

class HomeController extends Controller
{
    public function index()
    {
        // Mengambil produk terbaru untuk ditampilkan di halaman home
        $products = Product::latest()->take(8)->get();

        return view('home', compact('products'));
    }
}
