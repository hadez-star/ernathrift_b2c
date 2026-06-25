# BAB 5: KESIMPULAN DAN SARAN

## 5.1 Kesimpulan

Berdasarkan hasil penelitian dan pengembangan sistem informasi penjualan *thrift store* berbasis web pada **ERNA Thrifting** menggunakan framework Laravel 11, dapat ditarik kesimpulan sebagai berikut:

1. **Berhasil dibangunnya platform e-commerce thrift store** yang meliputi sistem manajemen produk, kategori, voucher, *flash sale*, keranjang belanja, *checkout*, pembayaran, serta manajemen pesanan. Sistem ini mencakup 15 model data (*User*, *Product*, *Category*, *Cart*, *Order*, *OrderItem*, *Review*, *Wishlist*, *Voucher*, *FlashSale*, *FlashSaleItem*, *ProductVariant*, *ProductImage*, *WebSetting*, dan *Notification*) yang saling terintegrasi untuk mendukung seluruh alur bisnis toko *thrift* secara digital.

2. **Sistem menyediakan dua panel pengguna yang terpisah**, yaitu:
   - **Panel Pengguna (User)** yang mencakup halaman utama (katalog produk, *flash sale*), fitur belanja (keranjang, *wishlist*, *checkout*), manajemen akun (profil, ERNA Pay, VIP *membership*), serta riwayat pesanan dan notifikasi.
   - **Panel Admin** yang mencakup *dashboard* analitik (grafik pendapatan, produk terlaris, distribusi status pesanan), manajemen CRUD (produk, kategori, voucher, *flash sale*), pengelolaan pesanan (konfirmasi, input resi, penanganan retur), serta cetak laporan PDF, invoice, dan resi pengiriman.

3. **Sistem berhasil mengimplementasikan fitur-fitur spesifik domain thrift store**, antara lain:
   - Sistem *flash sale* dengan waktu terbatas dan kuota stok khusus.
   - Program VIP *membership* tiga tingkatan (REGULER, SILVER, GOLD) dengan benefit berbeda berupa diskon dan gratis ongkos kirim.
   - Dompet digital internal (ERNA Pay) untuk kemudahan transaksi.
   - Sistem *voucher* diskon dengan berbagai jenis (persentase dan nominal) serta batasan penggunaannya.
   - Sistem retur barang dengan unggahan bukti foto.

4. **Sistem menerapkan pendekatan *route-closures*** pada arsitektur aplikasi, di mana sebagian besar logika bisnis ditulis langsung di dalam *routes/web.php* tanpa melalui *controller* secara terpisah.

## 5.2 Saran

Berdasarkan hasil penelitian dan pengembangan yang telah dilakukan, terdapat beberapa saran untuk pengembangan sistem selanjutnya:

1. **Refaktorisasi Arsitektur MVC** — Disarankan untuk memindahkan logika bisnis dari *route closures* ke dalam *controller* dan *service layer* yang terpisah. Hal ini akan meningkatkan *maintainability*, *testability*, dan *readability* kode, serta memudahkan pengembangan fitur di masa mendatang.

2. **Peningkatan Keamanan** — Mengganti penggunaan `$guarded = []` pada model dengan *mass assignment protection* yang lebih ketat melalui `$fillable` atau *form request validation*. Selain itu, perlu ditambahkan proteksi CSRF yang lebih ketat, *rate limiting* pada endpoint publik, serta validasi input yang lebih komprehensif.

3. **Penambahan Fitur Reset Password** — Sistem saat ini belum memiliki fitur *forgot/reset password* yang merupakan kebutuhan dasar aplikasi web modern. Fitur ini perlu segera ditambahkan.

4. **Pembangunan REST API** — Disarankan untuk membangun *RESTful API* terpisah pada `routes/api.php` guna mendukung pengembangan aplikasi *mobile* (Android/iOS) di masa mendatang serta integrasi dengan layanan pihak ketiga.

5. **Pengujian (Testing)** — Perlu dilakukan penambahan *unit test* dan *feature test* menggunakan PHPUnit untuk memastikan stabilitas sistem, terutama pada modul-modul kritis seperti *checkout*, pembayaran, dan manajemen pesanan.

6. **Optimasi Kinerja** — Implementasi *caching* (menggunakan Redis atau database *cache*) untuk data yang jarang berubah seperti kategori produk dan pengaturan web, serta pengindeksan (*indexing*) pada kolom-kolom database yang sering digunakan dalam pencarian.

7. **Integrasi Pembayaran** — Penggunaan *payment gateway* pihak ketiga (seperti Midtrans, Xendit, atau Tripay) untuk mengotomatisasi proses pembayaran dan mengurangi risiko kesalahan manual pada sistem pembayaran saat ini.

8. **Dokumentasi Kode** — Penambahan *comments* dan *PHPDoc* yang memadai pada kode, terutama pada bagian-bagian logika bisnis yang kompleks, serta pembuatan dokumentasi teknis menggunakan API documentation generator (seperti Swagger/OpenAPI).
