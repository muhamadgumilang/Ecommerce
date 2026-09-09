<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    // ✅ Tambahkan 'slug' ke fillable
    protected $fillable = [
        'name',
        'slug',
    ];

    public function getRouteKeyName()
    {
        return 'category_id';
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}
