<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('membership:check', function () {
    $this->info('Checking membership expiry...');
    
    // 1. Check for warning: 3 days before expiry
    $warningDate = Carbon::today()->addDays(3)->toDateString();
    $usersToWarn = User::where('vip_paket', '!=', 'REGULER')
        ->where('member_until', $warningDate)
        ->get();
        
    foreach ($usersToWarn as $user) {
        Notification::kirim($user->id, [
            'type' => 'system',
            'title' => 'Membership VIP Segera Berakhir',
            'message' => "Membership VIP {$user->vip_paket} Anda akan berakhir dalam 3 hari pada " . Carbon::parse($user->member_until)->locale('id')->translatedFormat('d F Y') . ". Silakan top up saldo dan perpanjang membership Anda!",
            'url' => url('/membership-vip'),
            'icon' => 'fa-gem',
            'color' => '#D4AF37',
        ]);
        $this->info("Warning sent to User ID: {$user->id} (Expiry: {$user->member_until})");
    }
    
    // 2. Check for expired memberships: expired today or earlier
    $usersToExpire = User::where('vip_paket', '!=', 'REGULER')
        ->where('member_until', '<', Carbon::today()->toDateString())
        ->get();
        
    foreach ($usersToExpire as $user) {
        $oldPaket = $user->vip_paket;
        $user->update([
            'vip_paket' => 'REGULER',
            'member_until' => null,
        ]);
        
        Notification::kirim($user->id, [
            'type' => 'system',
            'title' => 'Membership VIP Berakhir',
            'message' => "Masa aktif membership VIP {$oldPaket} Anda telah habis. Status Anda telah kembali menjadi REGULER.",
            'url' => url('/membership-vip'),
            'icon' => 'fa-exclamation-circle',
            'color' => '#E84C3D',
        ]);
        $this->info("Expired and downgraded User ID: {$user->id}");
    }
    
    $this->info('Membership check completed.');
})->purpose('Check and manage VIP membership expiry warnings and downgrades')->daily();
