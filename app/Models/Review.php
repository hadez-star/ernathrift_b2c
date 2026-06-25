<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Izinkan semua kolom untuk diisi (Mass Assignment)
    protected $guarded = ['id'];

    /**
     * Relasi ke tabel users (Pelanggan yang memberi ulasan)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke tabel products (Produk yang diulas)
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}