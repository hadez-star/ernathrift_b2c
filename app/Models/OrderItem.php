<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi secara otomatis saat proses checkout
    protected $guarded = [];

    // Relasi: Barang ini milik nota pesanan (Order) yang mana
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relasi: Mengambil data detail produk (Nama, Gambar, dll) dari tabel Products
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}