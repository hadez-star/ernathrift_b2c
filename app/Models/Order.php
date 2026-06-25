<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Mengizinkan semua kolom diisi secara otomatis saat proses checkout
    protected $guarded = [];

    // Relasi: Satu nota pesanan (Order) bisa berisi banyak barang (Order Items)
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi: Nota pesanan ini milik siapa (User)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}