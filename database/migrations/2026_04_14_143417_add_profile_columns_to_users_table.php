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
            // Cek satu per satu, jika belum ada baru ditambahkan
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'house_number')) {
                $table->string('house_number')->nullable();
            }
            
            if (!Schema::hasColumn('users', 'zip_code')) {
                $table->string('zip_code')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom hanya jika kolom tersebut memang ada
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('users', 'house_number')) {
                $table->dropColumn('house_number');
            }
            if (Schema::hasColumn('users', 'zip_code')) {
                $table->dropColumn('zip_code');
            }
        });
    }
};