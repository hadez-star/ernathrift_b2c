<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CartsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('carts')->delete();
        
        \DB::table('carts')->insert(array (
            0 => 
            array (
                'id' => 43,
                'user_id' => 2,
                'product_id' => 30,
                'jumlah' => 1,
                'created_at' => '2026-06-19 18:43:36',
                'updated_at' => '2026-06-19 18:43:36',
                'product_variant_id' => NULL,
            ),
        ));
        
        
    }
}