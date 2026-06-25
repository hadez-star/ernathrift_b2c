<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            
            // Menyambungkan dengan tabel orders (Nota pesanan)
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            
            // Menyambungkan dengan tabel products (Barang apa yang dibeli)
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Menyimpan jumlah barang dan harga saat barang itu dibeli
            $table->integer('jumlah');
            $table->integer('harga_satuan');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};