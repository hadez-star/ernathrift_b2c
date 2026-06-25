<?php
require '/var/www/vendor/autoload.php';
$app = require_once '/var/www/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();
echo 'SERVER_KEY=' . config('midtrans.server_key') . "\n";
echo 'CLIENT_KEY=' . config('midtrans.client_key') . "\n";
echo 'SNAP_URL=' . config('midtrans.snap_url') . "\n";
echo 'IS_PROD=' . (config('midtrans.is_production') ? 'true' : 'false') . "\n";
