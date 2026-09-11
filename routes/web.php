<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController as UserOrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController as PublicProductController;
use App\Http\Controllers\Admin\DashboardController; // Pastikan controller ini di-import
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use Illuminate\Support\Facades\Route;

// Halaman Utama (Landing Page) - Mengambil data produk via HomeController
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products/{product}', [PublicProductController::class, 'show'])->name('products.show');

Route::apiResource('catalog', CatalogController::class)->only(['index', 'show']);

// Dashboard hanya tersedia untuk admin.
Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

// Route Manajemen Admin (Dashboard, Kategori, Produk, Pesanan Admin)
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Diubah dari Route::view menjadi pemanggilan DashboardController@index
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::apiResource('catalogs', CatalogController::class)->except(['index', 'show']);
    Route::resource('orders', AdminOrderController::class)->except(['create', 'store']);
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::patch('/payments/{payment}', [AdminPaymentController::class, 'update'])->name('payments.update');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
});

// Route Manajemen Seller (Produk Toko, Pesanan Masuk Toko)
Route::middleware(['auth', 'verified', 'seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::resource('products', SellerProductController::class);
    Route::get('/orders', [SellerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [SellerOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/ship', [SellerOrderController::class, 'ship'])->name('orders.ship');
});

// Route Manajemen Profil User, Keranjang, Checkout, & Pesanan User
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buy-now');
    Route::post('/cart/checkout-selected', [CartController::class, 'checkoutSelected'])->name('cart.checkout-selected');
    Route::post('/cart/checkout-all', [CartController::class, 'checkoutAll'])->name('cart.checkout-all');
    Route::patch('/cart/items/{cart_item_id}', [CartController::class, 'updateQuantity'])->name('cart.update');
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

Route::post('/midtrans/notification', [PaymentController::class, 'notification'])
    ->name('midtrans.notification');
