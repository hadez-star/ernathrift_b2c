# Deploy Thrift Store to Coolify

Dokumen ini menjelaskan cara deploy aplikasi **Thrift Store** ini ke [Coolify](https://coolify.io/) menggunakan konfigurasi Docker yang sudah disediakan.

## 1. Persyaratan
Pastikan Anda sudah memiliki:
1. Server VPS dengan Coolify terinstall.
2. Repository ini sudah di-push ke GitHub / GitLab.
3. Database MySQL/MariaDB yang sudah dibuat di Coolify (atau external) dan siap digunakan.

## 2. Cara Menambahkan Project di Coolify
1. Buka Dashboard Coolify.
2. Buka menu **Projects**, buat project baru (atau pilih yang sudah ada), dan tambahkan **New Resource** -> **Application** -> **Public/Private Repository** (sesuaikan dengan status repo Anda).
3. Pilih repository ini dan branch yang ingin di-deploy (misalnya `main`).

## 3. Konfigurasi Deployment
Pada halaman konfigurasi aplikasi di Coolify, atur hal-hal berikut:

- **Build Pack**: Pilih `Docker Compose` atau biarkan default jika Coolify otomatis mendeteksi `Dockerfile`. Coolify secara otomatis akan mengenali `Dockerfile` yang ada di root direktori.
- **Port**: Set ke `80` atau biarkan default port mapping yang diberikan Coolify. (Image `serversideup/php-nginx` berjalan di port `8080` secara internal, Coolify biasanya akan mapping dengan benar, pastikan Expose Port adalah `8080`).

## 4. Environment Variables (.env)
Buka tab **Environment Variables** di Coolify, lalu tambahkan semua variabel yang ada di file `.env` lokal Anda. 
Beberapa variabel penting yang harus diperbarui:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://nama-domain-anda.com

# Sesuaikan dengan Database dari Coolify
DB_CONNECTION=mysql
DB_HOST=mysql-database-wq8chu0416tjz05wuc1u1u20
DB_PORT=3306
DB_DATABASE=default
DB_USERNAME=mysql
DB_PASSWORD=HSxeYsyh56djAG4EHkmaUvxAuY5isbCG... # (Silakan copy keseluruhan password dari tombol mata yang ada di Dashboard)

# Variable tambahan (Sudah di set di Dockerfile tapi bisa dioverride)
AUTORUN_LARAVEL_MIGRATION=true
AUTORUN_LARAVEL_STORAGE_LINK=true
```

## 5. Persistent Storage (Sangat Penting!)
Agar foto-foto produk, profil, dan ulasan yang baru di-upload tidak hilang saat aplikasi di-restart atau di-deploy ulang, Anda **wajib** menambahkan *Persistent Storage* di dashboard Coolify.

1. Buka tab **Persistent Storage** di aplikasi Coolify Anda.
2. Tambahkan storage baru dengan konfigurasi berikut:
   - **Source**: (Bisa dikosongkan/biarkan default agar Coolify membuat volume otomatis)
   - **Destination**: `/var/www/html/public/uploads`
3. Klik tombol **Save**.

## 6. Deploy & Seed Data
1. Klik tombol **Deploy** dan tunggu proses build & startup selesai.
2. Karena di environment variable kita mengatur `AUTORUN_LARAVEL_MIGRATION=true`, maka saat container berhasil jalan, migrasi database akan otomatis dieksekusi.
3. Untuk memasukkan **Seed Data** agar data sama seperti di lokal Anda, buka tab **Terminal/Execute Command** di aplikasi Coolify Anda, lalu ketikkan:
   ```bash
   php artisan db:seed --force
   ```
   > **Catatan:** Seed ini aman karena di `DatabaseSeeder.php` sudah disetel *kondisi khusus* agar data hanya masuk jika database masih kosong (mencegah duplikasi).

## 6. Selesai
Buka URL aplikasi Anda untuk memastikan semuanya berjalan dengan normal. Selamat, aplikasi Thrift Store Anda sudah online!
