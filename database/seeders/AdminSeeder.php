<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Buat akun admin default untuk sistem.
     * Jalankan dengan: php artisan db:seed --class=AdminSeeder
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada, jika belum buat baru
        $existing = User::where('email', 'admin@ernathrifting.com')->first();

        if (!$existing) {
            User::create([
                'name'      => 'Administrator',
                'email'     => 'admin@ernathrifting.com',
                'password'  => Hash::make('admin123456'),
                'role'      => 'admin',
                'saldo'     => 0,
                'vip_paket' => 'REGULER',
            ]);
            $this->command->info('✅ Akun admin berhasil dibuat!');
            $this->command->info('   Email   : admin@ernathrifting.com');
            $this->command->info('   Password: admin123456');
            $this->command->warn('   ⚠️  Segera ganti password setelah login!');
        } else {
            $this->command->warn('ℹ️  Akun admin sudah ada, tidak perlu dibuat ulang.');
        }
    }
}
