<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type')->default('info');         // order, retur, promo, system
            $table->string('title');                          // "Pesanan Dikemas"
            $table->text('message');                          // "Pesanan #INV/... sedang dikemas"
            $table->string('url')->nullable();                // Link tujuan saat diklik
            $table->string('icon')->default('fa-bell');       // Font Awesome icon class
            $table->string('color')->default('#D4AF37');      // Warna ikon
            $table->boolean('is_read')->default(false);       // Sudah dibaca atau belum
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

