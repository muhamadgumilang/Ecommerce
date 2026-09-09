<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController as UserOrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Import Admin Controllers dengan Alias
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

// Halaman Utama (Landing Page) - Bisa diakses siapa saja
Route::get('/', function () {
    return view('home');
});

// Tambahkan baris ini di routes/web.php
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route Manajemen Admin (Dashboard, Kategori, Produk, Pesanan Admin)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/dashboard', 'admin.dashboard')->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store']);
});

// Route Manajemen Profil User, Keranjang, Checkout, & Pesanan User
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/items/{cart_item_id}', [CartController::class, 'removeItem'])->name('cart.remove');

    Route::get('/checkout', [UserOrderController::class, 'showCheckout'])->name('checkout.index');
    Route::post('/checkout', [UserOrderController::class, 'processCheckout'])->name('checkout.process');

    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [UserOrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/payment', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/orders/{order}/payment', [PaymentController::class, 'store'])->name('payments.store');
});

// Memuat route bawaan Laravel Breeze (Login, Register, Logout, dll)
require __DIR__.'/auth.php';
