<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi User sebagai Penjual
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id', 'user_id');
    }

    // Relasi User sebagai Customer
    public function cart()
    {
        return $this->hasOne(Cart::class, 'customer_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'user_id');
    }

    // Relasi User sebagai Admin yang memverifikasi Pembayaran
    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'admin_id', 'user_id');
    }
}