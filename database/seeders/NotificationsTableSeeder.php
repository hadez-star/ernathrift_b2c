<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NotificationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('notifications')->delete();
        
        \DB::table('notifications')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => 2,
                'type' => 'order',
                'title' => 'Pesanan Dikemas',
                'message' => 'Pesanan INV/20260514/454 sedang disiapkan dan dikemas.',
                'url' => 'http://127.0.0.1:8000/pesanan/lacak/17',
                'icon' => 'fa-box',
                'color' => '#D4AF37',
                'is_read' => 1,
                'created_at' => '2026-05-14 16:40:54',
                'updated_at' => '2026-05-15 22:11:56',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'type' => 'order',
                'title' => 'Pesanan Dikirim',
                'message' => 'Pesanan INV/20260514/454 telah dikirimkan dengan resi 666.',
                'url' => 'http://127.0.0.1:8000/pesanan/lacak/17',
                'icon' => 'fa-shipping-fast',
                'color' => '#3498DB',
                'is_read' => 1,
                'created_at' => '2026-05-14 16:41:43',
                'updated_at' => '2026-05-15 22:11:56',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => 2,
                'type' => 'retur',
                'title' => 'Retur Diajukan',
                'message' => 'Retur untuk INV/20260514/454 sedang diproses.',
                'url' => 'http://127.0.0.1:8000/riwayat-pesanan',
                'icon' => 'fa-undo',
                'color' => '#9B59B6',
                'is_read' => 1,
                'created_at' => '2026-05-14 17:21:55',
                'updated_at' => '2026-05-15 22:11:56',
            ),
            3 => 
            array (
                'id' => 4,
                'user_id' => 2,
                'type' => 'retur',
                'title' => 'Retur Ditolak',
                'message' => 'Pengajuan retur untuk pesanan INV/20260514/454 ditolak.',
                'url' => 'http://127.0.0.1:8000/riwayat-pesanan',
                'icon' => 'fa-undo',
                'color' => '#E74C3C',
                'is_read' => 1,
                'created_at' => '2026-05-14 17:22:31',
                'updated_at' => '2026-05-15 22:11:56',
            ),
            4 => 
            array (
                'id' => 6,
                'user_id' => 6,
                'type' => 'order',
                'title' => 'Pesanan Dikemas',
                'message' => 'Pesanan INV/20260602/481 sedang disiapkan dan dikemas.',
                'url' => 'http://127.0.0.1:8000/pesanan/lacak/21',
                'icon' => 'fa-box',
                'color' => '#D4AF37',
                'is_read' => 1,
                'created_at' => '2026-06-02 17:54:03',
                'updated_at' => '2026-06-02 17:55:13',
            ),
            5 => 
            array (
                'id' => 7,
                'user_id' => 6,
                'type' => 'order',
                'title' => 'Pesanan Selesai',
                'message' => 'Pesanan INV/20260602/481 telah sampai dan selesai. Terima kasih!',
                'url' => 'http://127.0.0.1:8000/pesanan/lacak/21',
                'icon' => 'fa-check-circle',
                'color' => '#2ECC71',
                'is_read' => 0,
                'created_at' => '2026-06-02 17:56:36',
                'updated_at' => '2026-06-02 17:56:36',
            ),
            6 => 
            array (
                'id' => 8,
                'user_id' => 6,
                'type' => 'order',
                'title' => 'Pesanan Selesai',
                'message' => 'Pesanan INV/20260622/407 telah sampai dan selesai. Terima kasih!',
                'url' => 'http://127.0.0.1:8000/pesanan/lacak/22',
                'icon' => 'fa-check-circle',
                'color' => '#2ECC71',
                'is_read' => 0,
                'created_at' => '2026-06-22 22:47:24',
                'updated_at' => '2026-06-22 22:47:24',
            ),
            7 => 
            array (
                'id' => 9,
                'user_id' => 4,
                'type' => 'order',
                'title' => 'Pesanan Dikemas',
                'message' => 'Pesanan INV/20260623/0C34E3 sedang disiapkan dan dikemas.',
                'url' => 'http://127.0.0.1:8000/pesanan/lacak/23',
                'icon' => 'fa-box',
                'color' => '#D4AF37',
                'is_read' => 1,
                'created_at' => '2026-06-23 02:29:06',
                'updated_at' => '2026-06-23 02:30:05',
            ),
            8 => 
            array (
                'id' => 10,
                'user_id' => 4,
                'type' => 'order',
                'title' => 'Pesanan Dikirim',
                'message' => 'Pesanan INV/20260623/0C34E3 telah dikirimkan dengan resi 1234.',
                'url' => 'http://127.0.0.1:8000/pesanan/lacak/23',
                'icon' => 'fa-shipping-fast',
                'color' => '#3498DB',
                'is_read' => 1,
                'created_at' => '2026-06-23 02:29:15',
                'updated_at' => '2026-06-23 02:30:05',
            ),
            9 => 
            array (
                'id' => 11,
                'user_id' => 4,
                'type' => 'order',
                'title' => 'Pesanan Selesai',
                'message' => 'Pesanan INV/20260623/0C34E3 telah sampai dan selesai. Terima kasih!',
                'url' => 'http://127.0.0.1:8000/pesanan/lacak/23',
                'icon' => 'fa-check-circle',
                'color' => '#2ECC71',
                'is_read' => 1,
                'created_at' => '2026-06-23 02:29:22',
                'updated_at' => '2026-06-23 02:30:05',
            ),
            10 => 
            array (
                'id' => 12,
                'user_id' => 4,
                'type' => 'retur',
                'title' => 'Retur Diajukan',
                'message' => 'Retur untuk INV/20260623/0C34E3 sedang diproses.',
                'url' => 'http://127.0.0.1:8000/riwayat-pesanan',
                'icon' => 'fa-undo',
                'color' => '#9B59B6',
                'is_read' => 1,
                'created_at' => '2026-06-23 02:29:35',
                'updated_at' => '2026-06-23 02:30:05',
            ),
            11 => 
            array (
                'id' => 13,
                'user_id' => 4,
                'type' => 'retur',
                'title' => 'Retur Ditolak',
                'message' => 'Pengajuan retur untuk pesanan INV/20260623/0C34E3 ditolak.',
                'url' => 'http://127.0.0.1:8000/riwayat-pesanan',
                'icon' => 'fa-undo',
                'color' => '#E74C3C',
                'is_read' => 1,
                'created_at' => '2026-06-23 02:29:44',
                'updated_at' => '2026-06-23 02:30:05',
            ),
        ));
        
        
    }
}