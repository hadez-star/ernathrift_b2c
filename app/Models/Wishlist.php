<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    // BARIS INI YANG MEMPERBAIKI ERROR TERSEBUT
    // Mengizinkan semua kolom untuk diisi (Mass Assignment)
    protected $guarded = [];

    // Relasi ke tabel User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabel Product
    public function product() {
        return $this->belongsTo(Product::class);
    }
}