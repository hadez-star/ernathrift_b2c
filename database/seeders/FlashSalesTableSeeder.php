<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FlashSalesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('flash_sales')->delete();
        
        \DB::table('flash_sales')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nama_kampanye' => 'penghabisan stok',
                'start_time' => '2026-05-14 04:31:00',
                'end_time' => '2026-05-14 04:34:00',
                'is_active' => 0,
                'created_at' => '2026-04-19 18:53:17',
                'updated_at' => '2026-05-14 04:31:33',
            ),
        ));
        
        
    }
}