<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_items')->delete();
        
        \DB::table('order_items')->insert(array (
            0 => 
            array (
                'id' => 11,
                'order_id' => 11,
                'product_id' => 16,
                'jumlah' => 1,
                'harga_satuan' => 60000,
                'created_at' => '2026-04-22 17:31:18',
                'updated_at' => '2026-04-22 17:31:18',
                'product_variant_id' => NULL,
            ),
            1 => 
            array (
                'id' => 12,
                'order_id' => 12,
                'product_id' => 17,
                'jumlah' => 1,
                'harga_satuan' => 50000,
                'created_at' => '2026-04-22 17:32:34',
                'updated_at' => '2026-04-22 17:32:34',
                'product_variant_id' => NULL,
            ),
            2 => 
            array (
                'id' => 13,
                'order_id' => 13,
                'product_id' => 30,
                'jumlah' => 1,
                'harga_satuan' => 60000,
                'created_at' => '2026-04-22 17:43:19',
                'updated_at' => '2026-04-22 17:43:19',
                'product_variant_id' => NULL,
            ),
            3 => 
            array (
                'id' => 14,
                'order_id' => 14,
                'product_id' => 17,
                'jumlah' => 1,
                'harga_satuan' => 50000,
                'created_at' => '2026-04-22 17:47:45',
                'updated_at' => '2026-04-22 17:47:45',
                'product_variant_id' => NULL,
            ),
            4 => 
            array (
                'id' => 15,
                'order_id' => 15,
                'product_id' => 18,
                'jumlah' => 2,
                'harga_satuan' => 55000,
                'created_at' => '2026-05-03 13:15:33',
                'updated_at' => '2026-05-03 13:15:33',
                'product_variant_id' => NULL,
            ),
            5 => 
            array (
                'id' => 16,
                'order_id' => 16,
                'product_id' => 18,
                'jumlah' => 1,
                'harga_satuan' => 55000,
                'created_at' => '2026-05-14 03:48:55',
                'updated_at' => '2026-05-14 03:48:55',
                'product_variant_id' => NULL,
            ),
            6 => 
            array (
                'id' => 17,
                'order_id' => 16,
                'product_id' => 17,
                'jumlah' => 1,
                'harga_satuan' => 50000,
                'created_at' => '2026-05-14 03:48:55',
                'updated_at' => '2026-05-14 03:48:55',
                'product_variant_id' => NULL,
            ),
            7 => 
            array (
                'id' => 18,
                'order_id' => 17,
                'product_id' => 21,
                'jumlah' => 1,
                'harga_satuan' => 40000,
                'created_at' => '2026-05-14 16:40:13',
                'updated_at' => '2026-05-14 16:40:13',
                'product_variant_id' => NULL,
            ),
            8 => 
            array (
                'id' => 19,
                'order_id' => 18,
                'product_id' => 19,
                'jumlah' => 1,
                'harga_satuan' => 55000,
                'created_at' => '2026-05-15 22:02:23',
                'updated_at' => '2026-05-15 22:02:23',
                'product_variant_id' => NULL,
            ),
            9 => 
            array (
                'id' => 20,
                'order_id' => 19,
                'product_id' => 24,
                'jumlah' => 1,
                'harga_satuan' => 60000,
                'created_at' => '2026-05-15 22:06:31',
                'updated_at' => '2026-05-15 22:06:31',
                'product_variant_id' => NULL,
            ),
            10 => 
            array (
                'id' => 21,
                'order_id' => 20,
                'product_id' => 23,
                'jumlah' => 1,
                'harga_satuan' => 50000,
                'created_at' => '2026-05-16 02:16:17',
                'updated_at' => '2026-05-16 02:16:17',
                'product_variant_id' => NULL,
            ),
            11 => 
            array (
                'id' => 22,
                'order_id' => 21,
                'product_id' => 28,
                'jumlah' => 1,
                'harga_satuan' => 20000,
                'created_at' => '2026-06-02 17:51:56',
                'updated_at' => '2026-06-02 17:51:56',
                'product_variant_id' => NULL,
            ),
            12 => 
            array (
                'id' => 23,
                'order_id' => 22,
                'product_id' => 15,
                'jumlah' => 1,
                'harga_satuan' => 50000,
                'created_at' => '2026-06-22 22:46:35',
                'updated_at' => '2026-06-22 22:46:35',
                'product_variant_id' => NULL,
            ),
            13 => 
            array (
                'id' => 24,
                'order_id' => 23,
                'product_id' => 18,
                'jumlah' => 1,
                'harga_satuan' => 55000,
                'created_at' => '2026-06-23 02:28:48',
                'updated_at' => '2026-06-23 02:28:48',
                'product_variant_id' => NULL,
            ),
        ));
        
        
    }
}