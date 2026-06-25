<?php
require '/var/www/vendor/autoload.php';
$app = require_once '/var/www/bootstrap/app.php';
$app->make(Illuminate\Contracts\Http\Kernel::class)->bootstrap();

$orders = App\Models\Order::latest()->take(5)->get();
foreach ($orders as $o) {
    echo $o->id . ' | ' . $o->invoice . ' | ' . $o->status . ' | ' . ($o->metode_pembayaran ?? 'null') . ' | ' . $o->created_at . PHP_EOL;
}
