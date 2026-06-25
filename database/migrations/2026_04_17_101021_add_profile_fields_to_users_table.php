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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru dan mengizinkan kosong (nullable)
            $table->string('nomor_hp')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_rumah')->nullable();
            $table->string('kode_pos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn(['nomor_hp', 'alamat', 'no_rumah', 'kode_pos']);
        });
    }
};