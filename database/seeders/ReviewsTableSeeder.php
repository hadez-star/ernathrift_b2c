<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReviewsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('reviews')->delete();
        
        \DB::table('reviews')->insert(array (
            0 => 
            array (
                'id' => 2,
                'product_id' => 17,
                'user_id' => 2,
                'invoice' => 'INV/20260422/743',
                'rating' => 5,
                'komentar' => 'k',
                'foto' => NULL,
                'balasan_admin' => NULL,
                'created_at' => '2026-05-01 14:48:40',
                'updated_at' => '2026-05-01 14:48:40',
            ),
            1 => 
            array (
                'id' => 3,
                'product_id' => 18,
                'user_id' => 2,
                'invoice' => 'INV/20260503/321',
                'rating' => 3,
                'komentar' => 'Bahan nya bagus tapi ada beberapa yang bolong',
                'foto' => NULL,
                'balasan_admin' => 'oke terima kasih atas saran',
                'created_at' => '2026-05-03 13:34:15',
                'updated_at' => '2026-05-14 03:42:26',
            ),
            2 => 
            array (
                'id' => 4,
                'product_id' => 21,
                'user_id' => 2,
                'invoice' => 'INV/20260514/454',
                'rating' => 5,
                'komentar' => 'gg',
                'foto' => 'uploads/ulasan/1778753291_523_review_photo.jpg',
                'balasan_admin' => NULL,
                'created_at' => '2026-05-14 17:08:11',
                'updated_at' => '2026-05-14 17:08:11',
            ),
        ));
        
        
    }
}