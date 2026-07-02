<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VouchersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('vouchers')->delete();
        
        \DB::table('vouchers')->insert(array (
            0 => 
            array (
                'id' => 9,
                'code' => 'voo',
                'type' => 'fixed',
                'reward_amount' => 20000,
                'min_spend' => 0,
                'limit' => 0,
                'expiry_date' => 'Semua Pengguna',
                'valid_until' => '2026-04-22 14:05:00',
                'created_at' => '2026-04-22 13:57:26',
                'updated_at' => '2026-04-22 13:57:26',
            ),
            1 => 
            array (
                'id' => 11,
                'code' => 'tes',
                'type' => 'fixed',
                'reward_amount' => 20000,
                'min_spend' => 0,
                'limit' => 0,
                'expiry_date' => 'Semua Pengguna',
                'valid_until' => '2026-05-14 04:20:00',
                'created_at' => '2026-05-14 04:19:21',
                'updated_at' => '2026-05-14 04:19:21',
            ),
        ));
        
        
    }
}