<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('products')->delete();
        
        \DB::table('products')->insert(array (
            0 => 
            array (
                'id' => 15,
                'nama_produk' => 'Colombia',
                'slug' => 'colombia',
                'kategori' => 'baju pria',
                'harga' => 50000,
                'deskripsi' => 'belum di cuci',
            'gambar' => 'uploads/produk/1776796634_baju_colombia_(man).jpeg',
                'stok' => 1,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:37:14',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 16,
                'nama_produk' => 'The Beatles',
                'slug' => 'the-beatles',
                'kategori' => 'baju pria',
                'harga' => 60000,
            'deskripsi' => 'Lengan Panjang (Belum Di Cuci)',
                'gambar' => 'uploads/produk/1776796690_baju_cowok_the_beatles.jpeg',
                'stok' => 1,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:38:10',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 17,
                'nama_produk' => 'Narcotics',
                'slug' => 'narcotics',
                'kategori' => 'baju pria',
                'harga' => 50000,
                'deskripsi' => 'Belum Di Cuci',
            'gambar' => 'uploads/produk/1776796735_baju_narcotics(man).jpeg',
                'stok' => 0,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:38:55',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 18,
                'nama_produk' => 'Bintang',
                'slug' => 'bintang',
                'kategori' => 'baju pria',
                'harga' => 55000,
                'deskripsi' => 'Belum Di Cuci',
                'gambar' => 'uploads/produk/1776796769_baju_cowok_bintang.jpeg',
                'stok' => 1,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:39:29',
                'updated_at' => '2026-06-23 02:28:48',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 19,
                'nama_produk' => 'Pink',
                'slug' => 'pink',
                'kategori' => 'baju wanita',
                'harga' => 55000,
                'deskripsi' => 'Belum Di Cuci',
                'gambar' => 'uploads/produk/1776797478_baju_cewek.jpeg',
                'stok' => 0,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:51:18',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 20,
                'nama_produk' => 'Concert',
                'slug' => 'concert',
                'kategori' => 'baju wanita',
                'harga' => 55000,
                'deskripsi' => 'Belum Di Cuci',
                'gambar' => 'uploads/produk/1776797583_baju_cewek_concert.jpeg',
                'stok' => 1,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:53:03',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 21,
                'nama_produk' => 'Pouch',
                'slug' => 'pouch',
                'kategori' => 'baju wanita',
                'harga' => 40000,
                'deskripsi' => 'Belum Di Cuci',
            'gambar' => 'uploads/produk/1776797635_baju_cewek_(3).jpeg',
                'stok' => 0,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:53:55',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 22,
                'nama_produk' => 'Sweater',
                'slug' => 'sweater',
                'kategori' => 'baju wanita',
                'harga' => 50000,
                'deskripsi' => 'Belum Di Cuci',
            'gambar' => 'uploads/produk/1776797680_baju_cewek_(2).jpeg',
                'stok' => 1,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:54:40',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 23,
                'nama_produk' => 'Brown',
                'slug' => 'brown',
                'kategori' => 'celana wanita',
                'harga' => 50000,
                'deskripsi' => 'Belum Di Cuci',
                'gambar' => 'uploads/produk/1776797759_celana_cewek.jpeg',
                'stok' => 0,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:55:59',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 24,
                'nama_produk' => 'Chocolate',
                'slug' => 'chocolate',
                'kategori' => 'celana wanita',
                'harga' => 60000,
                'deskripsi' => 'Belum Di Cuci',
            'gambar' => 'uploads/produk/1776797915_celana_cewek_(4).jpeg',
                'stok' => 0,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:58:35',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 25,
                'nama_produk' => 'Milky',
                'slug' => 'milky',
                'kategori' => 'celana wanita',
                'harga' => 45000,
                'deskripsi' => 'Belum Di Cuci',
            'gambar' => 'uploads/produk/1776797946_celana_cewek_(3).jpeg',
                'stok' => 1,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:59:06',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 26,
                'nama_produk' => 'Navy blue',
                'slug' => 'navy-blue',
                'kategori' => 'celana wanita',
                'harga' => 55000,
                'deskripsi' => 'Belum Di Cuci',
            'gambar' => 'uploads/produk/1776797982_celana_cewek_(2).jpeg',
                'stok' => 1,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 01:59:42',
                'updated_at' => '2026-05-28 01:48:12',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 28,
                'nama_produk' => 'tes',
                'slug' => 'tes',
                'kategori' => 'Flash Sale',
                'harga' => 20000,
                'deskripsi' => 'zc',
                'gambar' => 'uploads/produk/1776841951_baju cewek concert.jpeg',
                'stok' => 0,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 0,
                'created_at' => '2026-04-22 14:12:31',
                'updated_at' => '2026-06-02 17:51:56',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 30,
                'nama_produk' => 'tes',
                'slug' => 'tes-1',
                'kategori' => 'baju pria',
                'harga' => 60000,
                'deskripsi' => 'cz',
                'gambar' => 'uploads/produk/1776842213_baju cowok the beatles.jpeg',
                'stok' => 1,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 1,
                'created_at' => '2026-04-22 14:16:53',
                'updated_at' => '2026-06-22 22:45:29',
                'deleted_at' => '2026-06-22 22:45:29',
            ),
            14 => 
            array (
                'id' => 31,
                'nama_produk' => 'BIntang',
                'slug' => 'bintang-1',
                'kategori' => 'baju pria',
                'harga' => 80000,
                'deskripsi' => 'Kondisi Baru',
                'gambar' => 'uploads/produk/1782155680_baju cowok bintang.jpeg',
                'stok' => 1,
                'foto' => NULL,
                'status' => 'Tersedia',
                'is_featured' => 1,
                'created_at' => '2026-06-23 02:14:40',
                'updated_at' => '2026-06-23 02:14:40',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}