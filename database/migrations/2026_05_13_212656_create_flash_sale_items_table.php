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
        Schema::table('flash_sales', function (Blueprint $table) {
            $table->string('nama_kampanye')->nullable()->after('id');
            $table->dateTime('start_time')->nullable()->after('nama_kampanye');
        });

        Schema::create('flash_sale_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flash_sale_id')->constrained('flash_sales')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->decimal('harga_diskon', 12, 2);
            $table->integer('kuota_stok')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sale_items');
        Schema::table('flash_sales', function (Blueprint $table) {
            $table->dropColumn(['nama_kampanye', 'start_time']);
        });
    }
};
