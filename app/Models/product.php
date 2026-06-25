<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Menggunakan guarded kosong agar semua kolom (nama_produk, harga, stok, dll)
     * bisa diisi secara otomatis dari form Admin.
     */
    protected $guarded = [];

    // Jika Anda ingin tetap menggunakan fillable, gunakan yang ini:
    /*
    protected $fillable = [
        'nama_produk',
        'kategori',
        'harga',
        'stok',
        'status',
        'deskripsi',
        'gambar',
    ];
    */

    // Mendaftarkan relasi: Satu Produk memiliki banyak Ulasan (Reviews)
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function flashSaleItems()
    {
        return $this->hasMany(FlashSaleItem::class);
    }
}