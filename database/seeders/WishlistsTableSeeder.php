<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WishlistsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('wishlists')->delete();
        
        \DB::table('wishlists')->insert(array (
            0 => 
            array (
                'id' => 16,
                'user_id' => 4,
                'product_id' => 18,
                'created_at' => '2026-06-23 02:20:23',
                'updated_at' => '2026-06-23 02:20:23',
            ),
        ));
        
        
    }
}