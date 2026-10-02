<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'seller_id',
        'category_id',
        'product_name',
        'slug',
        'price',
        'stock',
        'weight_gram',
        'description',
        'image',
        'images',
    ];

    protected $casts = [
        'weight_gram' => 'integer',
        'images' => 'array',
    ];

    protected static function booted(): void
    {
        static::saving(function (Product $product): void {
            if (! $product->slug || ($product->isDirty('product_name') && ! $product->isDirty('slug'))) {
                $baseSlug = Str::slug($product->product_name) ?: 'product';
                $slug = $baseSlug;
                $suffix = 2;

                while (static::query()->where('slug', $slug)
                    ->when($product->exists, fn ($query) => $query->where('product_id', '!=', $product->product_id))
                    ->exists()) {
                    $slug = $baseSlug.'-'.$suffix++;
                }

                $product->slug = $slug;
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id', 'product_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id', 'product_id');
    }
}
