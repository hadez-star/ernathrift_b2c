<?php
require '/var/www/vendor/autoload.php';
$app = require_once '/var/www/bootstrap/app.php';
$app->make(Illuminate\Contracts\Http\Kernel::class)->bootstrap();

// Update semua order Midtrans yang masih Menunggu Pembayaran ke Dikemas
$updated = App\Models\Order::where('status', 'Menunggu Pembayaran')
    ->where('metode_pembayaran', 'Midtrans')
    ->update(['status' => 'Dikemas']);

echo "Updated: " . $updated . " orders" . PHP_EOL;

// Tampilkan hasilnya
$orders = App\Models\Order::latest()->take(5)->get();
foreach ($orders as $o) {
    echo $o->id . ' | ' . $o->invoice . ' | ' . $o->status . ' | ' . ($o->metode_pembayaran ?? 'null') . PHP_EOL;
}
