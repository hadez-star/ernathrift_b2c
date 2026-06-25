<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Cek dan tambah satu per satu jika belum ada
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->text('alamat')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('users', 'no_rumah')) {
                $table->string('no_rumah')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('users', 'kode_pos')) {
                $table->string('kode_pos')->nullable()->after('no_rumah');
            }
            if (!Schema::hasColumn('users', 'foto')) {
                $table->string('foto')->nullable()->after('kode_pos');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('users', 'no_hp')) $columnsToDrop[] = 'no_hp';
            if (Schema::hasColumn('users', 'alamat')) $columnsToDrop[] = 'alamat';
            if (Schema::hasColumn('users', 'no_rumah')) $columnsToDrop[] = 'no_rumah';
            if (Schema::hasColumn('users', 'kode_pos')) $columnsToDrop[] = 'kode_pos';
            if (Schema::hasColumn('users', 'foto')) $columnsToDrop[] = 'foto';

            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};