<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('categories')->delete();
        
        \DB::table('categories')->insert(array (
            0 => 
            array (
                'id' => 2,
                'name' => 'baju pria',
                'slug' => 'baju-pria',
                'created_at' => '2026-04-19 18:19:28',
                'updated_at' => '2026-04-19 18:19:28',
            ),
            1 => 
            array (
                'id' => 4,
                'name' => 'celana pria',
                'slug' => 'celana-pria',
                'created_at' => '2026-04-20 14:30:20',
                'updated_at' => '2026-04-20 14:30:20',
            ),
            2 => 
            array (
                'id' => 5,
                'name' => 'baju wanita',
                'slug' => 'baju-wanita',
                'created_at' => '2026-04-20 18:37:29',
                'updated_at' => '2026-04-20 18:37:29',
            ),
            3 => 
            array (
                'id' => 6,
                'name' => 'celana wanita',
                'slug' => 'celana-wanita',
                'created_at' => '2026-04-22 01:55:14',
                'updated_at' => '2026-04-22 01:55:14',
            ),
        ));
        
        
    }
}