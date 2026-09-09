<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $primaryKey = 'category_id';

    // Sesuaikan dengan nama kolom yang ada di database MySQL Anda
    protected $fillable = [
        'category_name',
    ];

    public function getRouteKeyName()
    {
        return 'category_id';
    }

    // RELASI KE PRODUCT
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }
}