<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Snap token dari Midtrans (untuk re-open popup jika user belum bayar)
            $table->string('snap_token')->nullable()->after('status');
            // Midtrans order/transaction ID untuk keperluan lookup & webhook
            $table->string('midtrans_order_id')->nullable()->after('snap_token');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['snap_token', 'midtrans_order_id']);
        });
    }
};
