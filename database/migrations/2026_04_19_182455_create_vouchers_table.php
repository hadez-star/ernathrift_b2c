<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Contoh: DISKON10K
            $table->enum('type', ['fixed', 'percent']); // Potongan harga tetap atau persentase
            $table->integer('reward_amount'); // Nominal potongan (misal: 10000 atau 10)
            $table->integer('min_spend')->default(0); // Minimal belanja untuk pakai voucher
            $table->integer('limit')->default(0); // Batas berapa kali voucher bisa dipakai
            $table->date('expiry_date')->nullable(); // Tanggal kadaluarsa
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};