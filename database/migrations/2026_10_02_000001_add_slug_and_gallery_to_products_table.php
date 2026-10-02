<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique();
            $table->json('images')->nullable();
        });

        DB::table('products')->orderBy('product_id')->get()->each(function ($product): void {
            $baseSlug = Str::slug($product->product_name) ?: 'product';
            $slug = $baseSlug;
            $suffix = 2;

            while (DB::table('products')->where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $suffix++;
            }

            DB::table('products')->where('product_id', $product->product_id)->update([
                'slug' => $slug,
                'images' => json_encode($product->image ? [$product->image] : []),
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['slug', 'images']);
        });
    }
};