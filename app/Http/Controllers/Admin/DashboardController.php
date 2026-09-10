<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Menghitung total produk dari database
        $totalProducts = Product::count();

        return view('admin.dashboard', compact('totalProducts'));
    }
}
