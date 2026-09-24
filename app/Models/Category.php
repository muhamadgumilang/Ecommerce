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
        'owner_id',
        'name',
        'slug',
    ];

    protected $casts = [
        'owner_id' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'category_id';
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'category_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id', 'user_id');
    }
}
