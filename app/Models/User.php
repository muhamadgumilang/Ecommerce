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

    // Role helper methods
    public function isAdmin(): bool
    {
        return strtolower($this->role ?? '') === 'admin';
    }

    public function isSeller(): bool
    {
        return strtolower($this->role ?? '') === 'seller';
    }

    public function isCustomer(): bool
    {
        return strtolower($this->role ?? '') === 'customer';
    }

    public function hasRole(string $role): bool
    {
        return strtolower($this->role ?? '') === strtolower($role);
    }

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