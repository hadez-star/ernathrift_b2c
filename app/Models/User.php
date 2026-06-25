<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mengizinkan semua atribut/kolom untuk diisi secara otomatis (Mass Assignment)
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Pesanan (Orders)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relasi ke Keranjang (Cart)
     */
    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * Relasi ke Wishlist
     */
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}