<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

    // Gunakan $guarded = [] agar semua field bisa diisi,
    // KECUALI field yang tidak ada di tabel (gambar_tambahan ditangani manual di controller)
    protected $guarded = [];

    // Relasi ke gambar tambahan
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Relasi ke varian produk
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Relasi ke ulasan
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
