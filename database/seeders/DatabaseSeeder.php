<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Checkout;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. DUMMY USERS (Admin, Seller, Customer)
        $admin = User::create([
            'name' => 'Admin System',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'role' => 'Admin',
        ]);

        $seller = User::create([
            'name' => 'Penjual Toko Kita',
            'email' => 'seller@gmail.com',
            'password' => Hash::make('password123'),
            'phone' => '081298765432',
            'role' => 'Seller',
        ]);

        $customer = User::create([
            'name' => 'Budi Customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('password123'),
            'phone' => '085712345678',
            'role' => 'Customer',
        ]);

        // 2. DUMMY CATEGORIES
        $catElectronics = Category::create([
            'name' => 'Elektronik',
            'slug' => Str::slug('Elektronik')
        ]);

        $catFashion = Category::create([
            'name' => 'Pakaian',
            'slug' => Str::slug('Pakaian')
        ]);

        // 3. DUMMY PRODUCTS (Menggunakan getKey() agar aman terhadap nama primary key)
        $product1 = Product::create([
            'seller_id' => $seller->user_id,
            'category_id' => $catElectronics->getKey(),
            'product_name' => 'Laptop Gaming Pro',
            'price' => 15000000.00,
            'stock' => 10,
            'description' => 'Laptop spesifikasi tinggi untuk gaming dan desain.',
        ]);

        $product2 = Product::create([
            'seller_id' => $seller->user_id,
            'category_id' => $catFashion->getKey(),
            'product_name' => 'Kaos Oversize Hitam',
            'price' => 120000.00,
            'stock' => 50,
            'description' => 'Bahan katun combed 30s nyaman dipakai.',
        ]);

        // 4. DUMMY CART & CART ITEMS
        $cart = Cart::create([
            'customer_id' => $customer->user_id,
        ]);

        CartItem::create([
            'cart_id' => $cart->cart_id,
            'product_id' => $product2->product_id,
            'quantity' => 2,
        ]);

        // 5. DUMMY ORDER & CHECKOUT
        $order = Order::create([
            'customer_id' => $customer->user_id,
            'total_amount' => 15020000.00,
            'order_status' => 'Processing',
        ]);

        Checkout::create([
            'order_id' => $order->order_id,
            'shipping_address' => 'Jl. Merdeka No. 45, Jakarta Selatan',
            'courier' => 'JNE Express',
            'shipping_fee' => 20000.00,
            'notes' => 'Tolong packing kayu.',
        ]);

        // 6. DUMMY ORDER DETAILS
        OrderDetail::create([
            'order_id' => $order->order_id,
            'product_id' => $product1->product_id,
            'quantity' => 1,
            'subtotal' => 15000000.00,
        ]);

        // 7. DUMMY PAYMENT
        Payment::create([
            'order_id' => $order->order_id,
            'admin_id' => $admin->user_id,
            'payment_method' => 'Bank Transfer BCA',
            'payment_status' => 'Verified',
            'payment_date' => now(),
        ]);
    }
}
