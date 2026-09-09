<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');

            // Definisikan kolom foreign key secara manual dengan unsignedBigInteger
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('category_id');

            // Relasi ke tabel users (pastikan primary key users adalah user_id)
            $table->foreign('seller_id')
                  ->references('user_id')
                  ->on('users')
                  ->onDelete('cascade');

            // Relasi ke tabel categories dengan cascade delete
            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('categories')
                  ->onDelete('cascade');

            $table->string('product_name', 150);
            $table->decimal('price', 12, 2);
            $table->integer('stock')->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable(); // <-- Kolom foto produk ditambahkan di sini
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
