<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. DUMMY USERS (Admin, Seller, Customer)
        $admin = User::create([
            'name' => 'Admin System',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'role' => 'Admin',
        ]);

        $seller = User::create([
            'name' => 'Penjual Toko Kita',
            'email' => 'seller@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '081298765432',
            'role' => 'Seller',
        ]);

        $customer = User::create([
            'name' => 'Budi Customer',
            'email' => 'customer@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'phone' => '085712345678',
            'role' => 'Customer',
        ]);

        // 2. KATEGORI PRODUK
        $categories = collect([
            ['owner_id' => $admin->user_id, 'name' => 'Elektronik Admin', 'slug' => 'elektronik-admin'],
            ['owner_id' => $admin->user_id, 'name' => 'Fashion Admin', 'slug' => 'fashion-admin'],
            ['owner_id' => $admin->user_id, 'name' => 'Rumah Tangga Admin', 'slug' => 'rumah-tangga-admin'],
            ['owner_id' => $seller->user_id, 'name' => 'Elektronik Seller', 'slug' => 'elektronik-seller'],
            ['owner_id' => $seller->user_id, 'name' => 'Fashion Seller', 'slug' => 'fashion-seller'],
            ['owner_id' => $seller->user_id, 'name' => 'Kebutuhan Harian Seller', 'slug' => 'kebutuhan-harian-seller'],
        ])->mapWithKeys(function (array $category) {
            $createdCategory = Category::create($category);

            return [$category['slug'] => $createdCategory->category_id];
        });

        // 3. PRODUK CONTOH DENGAN GAMBAR DARI storage/app/public/products
        $products = [
            [
                'category' => 'elektronik-seller',
                'product_name' => 'Headphone Wireless Pro',
                'price' => 349000,
                'stock' => 24,
                'description' => 'Headphone wireless dengan suara jernih dan baterai tahan lama.',
                'image' => 'products/rn1wXbM7wHX2sF2jjnPxp9M3HByl1E9rzwIwFUU5.jpg',
            ],
            [
                'category' => 'elektronik-seller',
                'product_name' => 'Smartwatch Active Series',
                'price' => 599000,
                'stock' => 15,
                'description' => 'Smartwatch ringan untuk memantau aktivitas dan kesehatan harian.',
                'image' => 'products/tMfPPXrzuUqWpaOHqf6bPC0VUwniw8iteYl92TmT.jpg',
            ],
            [
                'category' => 'fashion-seller',
                'product_name' => 'Sneakers Urban Classic',
                'price' => 429000,
                'stock' => 18,
                'description' => 'Sneakers kasual dengan desain nyaman untuk aktivitas sehari-hari.',
                'image' => 'products/3es0yBvmMKfTljy0zYeOu5RjSEtDZl23dxDoTaKL.png',
            ],
            [
                'category' => 'fashion-seller',
                'product_name' => 'Jaket Casual Unisex',
                'price' => 289000,
                'stock' => 12,
                'description' => 'Jaket unisex berbahan ringan dengan gaya minimalis.',
                'image' => 'products/67Tv0OlRN7Bsfm3cCBoP43tttpe6o5w2W6nBulmp.png',
            ],
            [
                'category' => 'kebutuhan-harian-seller',
                'product_name' => 'Lampu Meja Minimalis',
                'price' => 159000,
                'stock' => 20,
                'description' => 'Lampu meja minimalis untuk ruang kerja dan kamar tidur.',
                'image' => 'products/8IBx3YApUD4gKbGVj2anb2MFw4wS9gq2oatDQGK5.png',
            ],
            [
                'category' => 'kebutuhan-harian-seller',
                'product_name' => 'Botol Minum Stainless',
                'price' => 119000,
                'stock' => 30,
                'description' => 'Botol minum stainless steel yang praktis dibawa bepergian.',
                'image' => 'products/9tv0dbP71EoJNwOgfLQ9hYaP1xUGOcveF2TrKhJa.png',
            ],
            [
                'category' => 'fashion-seller',
                'product_name' => 'Tas Selempang Daily',
                'price' => 199000,
                'stock' => 16,
                'description' => 'Tas selempang ringkas untuk menyimpan barang penting sehari-hari.',
                'image' => 'products/dGetgYdaWk4Jnp13rTbW01rtXFH3r9vj0USvd9kk.png',
            ],
            [
                'category' => 'fashion-seller',
                'product_name' => 'Kacamata Sunglasses',
                'price' => 149000,
                'stock' => 14,
                'description' => 'Kacamata sunglasses dengan desain modern dan nyaman dipakai.',
                'image' => 'products/jwF6IbsdrOniw6gSEgRsEzxFIXy9qXIIpOyu8zFh.png',
            ],
            [
                'category' => 'kebutuhan-harian-seller',
                'product_name' => 'Essential Oil Relaxing',
                'price' => 89000,
                'stock' => 25,
                'description' => 'Essential oil dengan aroma menenangkan untuk relaksasi di rumah.',
                'image' => 'products/kTlvpz6vhpBo1uXfpmil92160Fy8p8N3ekiUqLLz.png',
            ],
            [
                'category' => 'kebutuhan-harian-seller',
                'product_name' => 'Skincare Daily Set',
                'price' => 239000,
                'stock' => 10,
                'description' => 'Paket perawatan wajah harian untuk rutinitas kulit yang praktis.',
                'image' => 'products/nmhCmkkrsUVpYvrXTefqoqLEBZ1Ad3uUh5mt1by4.png',
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'seller_id' => $seller->user_id,
                'category_id' => $categories[$product['category']],
                'product_name' => $product['product_name'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'description' => $product['description'],
                'image' => $product['image'],
            ]);
        }

        $adminProducts = [
            ['category' => 'elektronik-admin', 'product_name' => 'Monitor Kerja Admin', 'price' => 1850000, 'stock' => 8, 'description' => 'Monitor berkualitas untuk kebutuhan kerja dan produktivitas.', 'image' => 'products/rn1wXbM7wHX2sF2jjnPxp9M3HByl1E9rzwIwFUU5.jpg'],
            ['category' => 'elektronik-admin', 'product_name' => 'Keyboard Mechanical Office', 'price' => 475000, 'stock' => 14, 'description' => 'Keyboard mechanical nyaman untuk bekerja dan mengetik.', 'image' => 'products/tMfPPXrzuUqWpaOHqf6bPC0VUwniw8iteYl92TmT.jpg'],
            ['category' => 'fashion-admin', 'product_name' => 'Kemeja Formal Premium', 'price' => 325000, 'stock' => 20, 'description' => 'Kemeja formal dengan bahan nyaman untuk aktivitas profesional.', 'image' => 'products/3es0yBvmMKfTljy0zYeOu5RjSEtDZl23dxDoTaKL.png'],
            ['category' => 'fashion-admin', 'product_name' => 'Sepatu Formal Kulit', 'price' => 685000, 'stock' => 9, 'description' => 'Sepatu formal dengan tampilan rapi dan sol yang nyaman.', 'image' => 'products/67Tv0OlRN7Bsfm3cCBoP43tttpe6o5w2W6nBulmp.png'],
            ['category' => 'rumah-tangga-admin', 'product_name' => 'Kursi Kerja Ergonomis', 'price' => 1290000, 'stock' => 6, 'description' => 'Kursi kerja ergonomis untuk mendukung posisi duduk yang nyaman.', 'image' => 'products/8IBx3YApUD4gKbGVj2anb2MFw4wS9gq2oatDQGK5.png'],
        ];

        foreach ($adminProducts as $product) {
            Product::create([
                'seller_id' => $admin->user_id,
                'category_id' => $categories[$product['category']],
                'product_name' => $product['product_name'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'description' => $product['description'],
                'image' => $product['image'],
            ]);
        }
    }
}
