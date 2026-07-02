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
];
foreach (\$vars as \$key => \$value) {
    if (\$value !== false && \$value !== '') {
        \$pattern = '/^' . preg_quote(\$key, '/') . '=.*/m';
        if (preg_match(\$pattern, \$env)) {
            \$env = preg_replace(\$pattern, \$key . '=' . \$value, \$env);
        } else {
            \$env .= PHP_EOL . \$key . '=' . \$value;
        }
    }
}
file_put_contents('.env', \$env);
echo 'ENV vars written to .env' . PHP_EOL;
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

# Clear config cache
php artisan config:clear

# Cek koneksi DB
echo "Mengecek koneksi database..."
php artisan db:show --no-interaction 2>/dev/null || echo "DB check skipped"

# Jalankan migration
echo "Menjalankan migration..."
php artisan migrate --force

# Storage link
php artisan storage:link --force 2>/dev/null || true

# Recreate storage skeleton if mounted as an empty volume
mkdir -p /var/www/storage/framework/cache/data
mkdir -p /var/www/storage/framework/sessions
mkdir -p /var/www/storage/framework/testing
mkdir -p /var/www/storage/framework/views
mkdir -p /var/www/storage/logs
mkdir -p /var/www/storage/app/public/uploads

# Fix permissions
chmod -R 775 storage bootstrap/cache public/uploads 2>/dev/null || true

echo "=== Server starting on port 8000 ==="
exec php artisan serve --host=0.0.0.0 --port=8000
