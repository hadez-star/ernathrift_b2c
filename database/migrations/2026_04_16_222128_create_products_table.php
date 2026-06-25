<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration (Membuat tabel).
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_produk');
            $table->string('kategori');
            $table->integer('harga');
            $table->string('foto')->nullable();
            
            // Kolom status untuk logika Thrifting (1-of-1)
            $table->enum('status', ['Tersedia', 'Dipesan', 'Terjual'])->default('Tersedia'); 
            
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration (Menghapus tabel jika di-rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};