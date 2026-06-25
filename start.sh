#!/bin/sh
set -e

echo "=== Starting ERNA Thrifting ==="

# Tulis APP_KEY dari env ke .env file jika ada
if [ ! -z "$APP_KEY" ]; then
    echo "APP_KEY ditemukan dari environment, menulis ke .env..."
    sed -i "s|^APP_KEY=.*|APP_KEY=$APP_KEY|" .env
else
    echo "APP_KEY tidak ada, generate baru..."
    php artisan key:generate --force
fi

# Tulis DB config dari env ke .env
if [ ! -z "$DB_HOST" ]; then
    sed -i "s|^DB_HOST=.*|DB_HOST=$DB_HOST|" .env
    sed -i "s|^DB_DATABASE=.*|DB_DATABASE=$DB_DATABASE|" .env
    sed -i "s|^DB_USERNAME=.*|DB_USERNAME=$DB_USERNAME|" .env
    sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" .env
fi

# Tulis Midtrans config
if [ ! -z "$MIDTRANS_SERVER_KEY" ]; then
    sed -i "s|^MIDTRANS_SERVER_KEY=.*|MIDTRANS_SERVER_KEY=$MIDTRANS_SERVER_KEY|" .env
    sed -i "s|^MIDTRANS_CLIENT_KEY=.*|MIDTRANS_CLIENT_KEY=$MIDTRANS_CLIENT_KEY|" .env
fi

# Clear config cache supaya baca .env yang sudah diupdate
php artisan config:clear

# Jalankan migration
echo "Menjalankan migration..."
php artisan migrate --force

# Storage link
php artisan storage:link --force 2>/dev/null || true

# Fix permissions
chmod -R 775 storage bootstrap/cache 2>/dev/null || true

echo "=== Server starting on port 8000 ==="
php artisan serve --host=0.0.0.0 --port=8000
