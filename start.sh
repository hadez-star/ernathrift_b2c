#!/bin/sh
set -e

echo "=== Starting ERNA Thrifting ==="

cd /var/www

# Tulis semua env ke .env file menggunakan PHP (lebih aman dari sed)
php -r "
\$env = file_get_contents('.env');
\$vars = [
    'APP_KEY'              => getenv('APP_KEY'),
    'APP_URL'              => getenv('APP_URL'),
    'APP_ENV'              => getenv('APP_ENV'),
    'APP_DEBUG'            => getenv('APP_DEBUG'),
    'DB_HOST'              => getenv('DB_HOST'),
    'DB_PORT'              => getenv('DB_PORT'),
    'DB_DATABASE'          => getenv('DB_DATABASE'),
    'DB_USERNAME'          => getenv('DB_USERNAME'),
    'DB_PASSWORD'          => getenv('DB_PASSWORD'),
    'MIDTRANS_SERVER_KEY'  => getenv('MIDTRANS_SERVER_KEY'),
    'MIDTRANS_CLIENT_KEY'  => getenv('MIDTRANS_CLIENT_KEY'),
    'MIDTRANS_IS_PRODUCTION' => getenv('MIDTRANS_IS_PRODUCTION'),
    'MIDTRANS_SNAP_URL'    => getenv('MIDTRANS_SNAP_URL'),
    'SESSION_DRIVER'       => getenv('SESSION_DRIVER'),
    'CACHE_STORE'          => getenv('CACHE_STORE'),
    'MAIL_MAILER'          => getenv('MAIL_MAILER'),
    'MAIL_HOST'            => getenv('MAIL_HOST'),
    'MAIL_PORT'            => getenv('MAIL_PORT'),
    'MAIL_USERNAME'        => getenv('MAIL_USERNAME'),
    'MAIL_PASSWORD'        => getenv('MAIL_PASSWORD'),
    'MAIL_ENCRYPTION'      => getenv('MAIL_ENCRYPTION'),
    'MAIL_FROM_ADDRESS'    => getenv('MAIL_FROM_ADDRESS'),
    'MAIL_FROM_NAME'       => getenv('MAIL_FROM_NAME'),
];
foreach (\$vars as \$key => \$value) {
    if (\$value !== false && \$value !== '') {
        \$pattern = '/^' . preg_quote(\$key, '/') . '=.*/m';
        // Wrap value in quotes if it contains spaces or special chars
        \$safeValue = (strpos(\$value, ' ') !== false || strpos(\$value, '#') !== false)
            ? '\"' . addslashes(\$value) . '\"'
            : \$value;
        if (preg_match(\$pattern, \$env)) {
            \$env = preg_replace(\$pattern, \$key . '=' . \$safeValue, \$env);
        } else {
            \$env .= PHP_EOL . \$key . '=' . \$safeValue;
        }
    }
}
file_put_contents('.env', \$env);
echo 'ENV vars written to .env' . PHP_EOL;
echo 'MAIL_MAILER from env: ' . getenv('MAIL_MAILER') . PHP_EOL;
echo 'MAIL_PASSWORD from env: ' . (getenv('MAIL_PASSWORD') ? 'SET' : 'NOT SET') . PHP_EOL;
"

# Generate key jika masih kosong
php -r "
\$env = file_get_contents('.env');
if (preg_match('/^APP_KEY=\$/m', \$env) || preg_match('/^APP_KEY=base64:Unsupported/m', \$env)) {
    echo 'APP_KEY kosong, generate baru...' . PHP_EOL;
}
"

# Pastikan APP_KEY valid
APP_KEY_VAL=$(php -r "
\$env = file_get_contents('.env');
preg_match('/^APP_KEY=(.+)$/m', \$env, \$m);
echo isset(\$m[1]) ? trim(\$m[1]) : '';
")

if [ -z "$APP_KEY_VAL" ] || [ "$APP_KEY_VAL" = "base64:" ]; then
    echo "APP_KEY invalid, generate baru..."
    php artisan key:generate --force
fi

# Recreate storage skeleton DULU sebelum artisan commands
mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/testing
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/logs
mkdir -p /var/www/storage/app/public/uploads
mkdir -p /var/www/bootstrap/cache

# Fix permissions
chmod -R 775 storage bootstrap/cache public/uploads 2>/dev/null || true

# Clear config cache
php artisan config:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true
php artisan cache:clear 2>/dev/null || true

# Cek koneksi DB
echo "Mengecek koneksi database..."
php artisan db:show --no-interaction 2>/dev/null || echo "DB check skipped"

# Jalankan migration
echo "Menjalankan migration..."
php artisan migrate --force

# Storage link
php artisan storage:link --force 2>/dev/null || true

echo "=== Server starting on port 8000 ==="
exec php artisan serve --host=0.0.0.0 --port=8000
