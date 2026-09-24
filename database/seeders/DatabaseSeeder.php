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
        // 1. DUMMY USERS (Admin, Seller, Customer) - Gunakan firstOrCreate untuk hindari duplicate
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin System',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'role' => 'Admin',
            ]
        );

        $seller = User::firstOrCreate(
            ['email' => 'seller@gmail.com'],
            [
                'name' => 'Penjual Toko Kita',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '081298765432',
                'role' => 'Seller',
            ]
        );

        $customer = User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'Budi Customer',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'phone' => '085712345678',
                'role' => 'Customer',
            ]
        );

        // 2. KATEGORI PRODUK - Gunakan firstOrCreate untuk hindari duplicate
        $categoryData = [
            ['owner_id' => $admin->user_id, 'name' => 'Elektronik Admin', 'slug' => 'elektronik-admin'],
            ['owner_id' => $admin->user_id, 'name' => 'Fashion Admin', 'slug' => 'fashion-admin'],
            ['owner_id' => $admin->user_id, 'name' => 'Rumah Tangga Admin', 'slug' => 'rumah-tangga-admin'],
            ['owner_id' => $seller->user_id, 'name' => 'Elektronik Seller', 'slug' => 'elektronik-seller'],
            ['owner_id' => $seller->user_id, 'name' => 'Fashion Seller', 'slug' => 'fashion-seller'],
            ['owner_id' => $seller->user_id, 'name' => 'Kebutuhan Harian Seller', 'slug' => 'kebutuhan-harian-seller'],
        ];

        $categories = collect();
        foreach ($categoryData as $cat) {
            $category = Category::firstOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
            $categories->put($cat['slug'], $category->category_id);
        }

        // 3. PRODUK CONTOH DENGAN GAMBAR DARI storage/app/public/products
        $products = [
            [
                'category' => 'elektronik-seller',
                'product_name' => 'iPhone 15 Pro',
                'price' => 349000,
                'stock' => 24,
                'description' => 'iPhone dengan desain premium, kamera canggih, dan performa cepat.',
                'image' => 'products/iphone.jpg',
            ],
            [
                'category' => 'elektronik-seller',
                'product_name' => 'Laptop HP Performance',
                'price' => 599000,
                'stock' => 15,
                'description' => 'Laptop HP untuk kebutuhan kerja, belajar, dan produktivitas sehari-hari.',
                'image' => 'products/laptop.jpg',
            ],
            [
                'category' => 'fashion-seller',
                'product_name' => 'Yamaha XSR',
                'price' => 429000,
                'stock' => 18,
                'description' => 'Motor Yamaha XSR dengan desain klasik modern dan performa tangguh.',
                'image' => 'products/xsr.jpg',
            ],
            [
                'category' => 'fashion-seller',
                'product_name' => 'Knit Sweater Premium',
                'price' => 289000,
                'stock' => 12,
                'description' => 'Sweater knit nyaman dengan desain minimalis untuk penggunaan sehari-hari.',
                'image' => 'products/knit baju.webp',
            ],
            [
                'category' => 'kebutuhan-harian-seller',
                'product_name' => 'Laptop HP Pavilion',
                'price' => 159000,
                'stock' => 20,
                'description' => 'Laptop HP dengan desain praktis untuk kerja dan aktivitas harian.',
                'image' => 'products/laptop.jpg',
            ],
            [
                'category' => 'kebutuhan-harian-seller',
                'product_name' => 'iPhone 15',
                'price' => 119000,
                'stock' => 30,
                'description' => 'iPhone dengan desain modern, kamera berkualitas, dan performa andal.',
                'image' => 'products/iphone.jpg',
            ],
            [
                'category' => 'fashion-seller',
                'product_name' => 'Knit Cardigan Casual',
                'price' => 199000,
                'stock' => 16,
                'description' => 'Cardigan knit lembut dengan gaya kasual untuk penggunaan sehari-hari.',
                'image' => 'products/knit baju.webp',
            ],
            [
                'category' => 'fashion-seller',
                'product_name' => 'Yamaha XSR 155',
                'price' => 149000,
                'stock' => 14,
                'description' => 'Yamaha XSR 155 dengan desain klasik modern dan performa bertenaga.',
                'image' => 'products/xsr.jpg',
            ],
            [
                'category' => 'kebutuhan-harian-seller',
                'product_name' => 'Knit Sweater Casual',
                'price' => 89000,
                'stock' => 25,
                'description' => 'Sweater knit hangat dan nyaman dengan gaya kasual modern.',
                'image' => 'products/knit baju.webp',
            ],
            [
                'category' => 'kebutuhan-harian-seller',
                'product_name' => 'iPhone Pro Max',
                'price' => 239000,
                'stock' => 10,
                'description' => 'iPhone seri Pro dengan kamera canggih dan performa premium.',
                'image' => 'products/iphone.jpg',
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['product_name' => $product['product_name']],
                [
                    'seller_id' => $seller->user_id,
                    'category_id' => $categories[$product['category']],
                    'product_name' => $product['product_name'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'description' => $product['description'],
                    'image' => $product['image'],
                ]
            );
        }

        $adminProducts = [
            ['category' => 'elektronik-admin', 'product_name' => 'Laptop Gaming', 'price' => 1850000, 'stock' => 8, 'description' => 'Laptop dengan performa tinggi untuk kerja dan hiburan.', 'image' => 'products/laptop.jpg'],
            ['category' => 'elektronik-admin', 'product_name' => 'iPhone Premium', 'price' => 475000, 'stock' => 14, 'description' => 'iPhone premium dengan desain elegan dan performa cepat.', 'image' => 'products/iphone.jpg'],
            ['category' => 'fashion-admin', 'product_name' => 'Knit Sweater', 'price' => 325000, 'stock' => 20, 'description' => 'Sweater knit nyaman dengan desain minimalis.', 'image' => 'products/knit baju.webp'],
            ['category' => 'fashion-admin', 'product_name' => 'Yamaha XSR Classic', 'price' => 685000, 'stock' => 9, 'description' => 'Yamaha XSR bergaya klasik dengan tampilan modern.', 'image' => 'products/xsr.jpg'],
            ['category' => 'rumah-tangga-admin', 'product_name' => 'Laptop Office', 'price' => 1290000, 'stock' => 6, 'description' => 'Laptop praktis untuk pekerjaan kantor dan produktivitas.', 'image' => 'products/laptop.jpg'],
        ];

        foreach ($adminProducts as $product) {
            Product::firstOrCreate(
                ['product_name' => $product['product_name']],
                [
                    'seller_id' => $admin->user_id,
                    'category_id' => $categories[$product['category']],
                    'product_name' => $product['product_name'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'description' => $product['description'],
                    'image' => $product['image'],
                ]
            );
        }

        // 4. SHIPPING COSTS (Ongkir RajaOngkir)
        $this->call(ShippingCostSeeder::class);
    }
}
