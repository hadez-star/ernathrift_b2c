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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Menyambungkan pesanan dengan user yang membelinya
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Kolom baru untuk sistem checkout
            $table->string('invoice')->unique();
            $table->text('alamat_pengiriman');
            $table->integer('total_harga');
            $table->integer('diskon')->default(0);
            $table->integer('ongkir')->default(0);
            $table->integer('total_bayar');
            
            // Kolom status untuk dilacak oleh Admin & Pembeli
            $table->string('status')->default('Dikemas'); // Dikemas, Dikirim, Selesai, Batal
            
            $table->timestamps();
        });
    }

    /**
     * Batalkan migration (Menghapus tabel jika di-rollback).
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};