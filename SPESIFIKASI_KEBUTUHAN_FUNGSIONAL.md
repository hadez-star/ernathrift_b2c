a.	Halaman Admin
1.	Sistem harus menyediakan fitur login bagi admin untuk mengakses seluruh halaman sistem.
    Penjelasan: Admin harus dapat masuk ke dalam sistem menggunakan email dan password yang terdaftar. Setelah login, admin diarahkan ke halaman dashboard.

2.	Sistem harus menyediakan fitur logout bagi admin untuk keluar dari sesi akun.
    Penjelasan: Admin dapat keluar dari sistem dengan klik tombol logout, yang akan menghapus sesi dan mengarahkan kembali ke halaman login.

3.	Sistem harus menampilkan halaman dashboard yang berisi statistik jumlah kategori, jumlah produk, jumlah pesanan per bulan, pendapatan per bulan, grafik penjualan dalam bentuk grafik garis, serta daftar 5 produk terlaris.
    Penjelasan: Dashboard admin menampilkan ringkasan data bisnis secara real-time berupa angka statistik dan visualisasi grafik untuk memantau kinerja toko.

4.	Sistem harus menyediakan fitur bagi admin untuk mengelola data kategori, meliputi menambah, mengubah, melihat, dan menghapus data kategori.
    Penjelasan: Admin dapat membuat kategori baru, mengedit nama kategori, melihat daftar kategori, dan menghapus kategori yang tidak digunakan.

5.	Sistem harus menyediakan fitur bagi admin untuk mengelola data varian produk (warna dan ukuran), meliputi menambah, mengubah, dan menghapus data varian yang terikat pada suatu produk.
    Penjelasan: Admin dapat menambahkan varian warna dan ukuran untuk setiap produk beserta stok masing-masing, serta mengedit atau menghapus varian tersebut.

6.	Sistem harus menyediakan fitur bagi admin untuk mengelola data produk, meliputi menambah, mengubah, melihat, dan menghapus data produk.
    Penjelasan: Admin dapat menambah produk baru dengan informasi lengkap (nama, harga, deskripsi, kategori), mengedit data produk yang sudah ada, melihat daftar produk, dan menghapus produk (soft delete).

7.	Sistem harus menyediakan fitur bagi admin untuk mengunggah gambar pada saat menambah atau mengubah data kategori dan produk dengan dilengkapi galeri gambar produk.
    Penjelasan: Admin dapat mengunggah gambar kategori dan menambahkan beberapa gambar untuk satu produk yang ditampilkan dalam bentuk galeri.

8.	Sistem harus menyediakan fitur bagi admin untuk mengelola data flash sale yang menampilkan daftar produk diskon dan keranjang flash sale secara visual.
    Penjelasan: Admin dapat membuat kampanye flash sale, memilih produk yang akan didiskon, menentukan harga diskon dan kuota stok, serta melihat daftar flash sale yang aktif.

9.	Sistem harus menyediakan fitur bagi admin untuk mengatur diskon flash sale dalam bentuk persentase maupun nominal pada saat pembuatan kampanye.
    Penjelasan: Admin dapat menentukan jenis diskon yang diterapkan pada produk flash sale, baik berupa potongan persentase (%) maupun potongan harga nominal (Rp).

10.	Sistem harus menyediakan fitur bagi admin untuk mengelola data voucher diskon dalam bentuk persentase maupun nominal beserta batasan penggunaannya.
    Penjelasan: Admin dapat membuat kode voucher dengan tipe diskon persen atau nominal, menentukan minimal belanja, batas pemakaian, dan masa berlaku voucher.

11.	Sistem harus mengirimkan notifikasi secara otomatis kepada pelanggan pada setiap perubahan status pesanan.
    Penjelasan: Sistem akan mengirim pemberitahuan ke pelanggan setiap kali admin mengubah status pesanan (diproses, dikemas, dikirim, selesai, dibatalkan).

12.	Sistem harus menghasilkan kode invoice secara otomatis pada setiap transaksi penjualan.
    Penjelasan: Setiap pesanan yang berhasil dibuat akan mendapatkan nomor invoice unik sebagai identitas transaksi.

13.	Sistem harus mengurangi stok produk secara otomatis pada saat transaksi penjualan berhasil diproses.
    Penjelasan: Ketika pesanan dikonfirmasi, sistem akan mengurangi jumlah stok produk sesuai dengan jumlah yang dibeli pelanggan.

14.	Sistem harus menampilkan halaman riwayat pesanan yang berisi daftar seluruh transaksi beserta detail item yang terjual.
    Penjelasan: Admin dapat melihat seluruh daftar pesanan yang masuk, status masing-masing, serta detail produk yang dipesan.

15.	Sistem harus menyediakan fitur bagi admin untuk mencari dan menyaring data pesanan berdasarkan periode waktu.
    Penjelasan: Admin dapat memfilter daftar pesanan berdasarkan rentang tanggal tertentu untuk memudahkan pencarian.

16.	Sistem harus menyediakan fitur bagi admin untuk mengekspor laporan penjualan ke dalam format PDF.
    Penjelasan: Admin dapat mengunduh laporan data penjualan dalam bentuk file PDF untuk keperluan arsip atau pelaporan.

17.	Sistem harus menyediakan fitur bagi admin untuk mencetak laporan penjualan dalam format A4 Potrait.
    Penjelasan: Admin dapat mencetak laporan penjualan langsung ke printer dengan format kertas A4 vertikal.

18.	Sistem harus menyediakan fitur bagi admin untuk melihat pratinjau invoice pesanan (receipt) dan mencetaknya.
    Penjelasan: Admin dapat melihat tampilan invoice sebelum dicetak dan mencetaknya untuk diberikan kepada pelanggan.

19.	Sistem harus menyediakan fitur bagi admin untuk membatalkan pesanan dan mengembalikan stok produk secara otomatis.
    Penjelasan: Jika admin membatalkan pesanan, sistem akan otomatis mengembalikan jumlah stok produk yang sebelumnya telah dikurangi.

20.	Sistem harus menyediakan fitur bagi admin untuk melihat dan membalas ulasan produk dari pelanggan.
    Penjelasan: Admin dapat melihat seluruh ulasan yang diberikan pelanggan, menyaring berdasarkan rating, dan memberikan balasan terhadap ulasan tersebut.

b.	Halaman Pelanggan
1.	Sistem harus menyediakan fitur register bagi pelanggan untuk mendaftar akun baru.
    Penjelasan: Pengguna baru dapat mendaftar dengan mengisi nama, email, dan password untuk membuat akun pelanggan.

2.	Sistem harus menyediakan fitur login bagi pelanggan untuk mengakses halaman yang sesuai dengan hak aksesnya.
    Penjelasan: Pelanggan yang sudah terdaftar dapat masuk menggunakan email dan password untuk mengakses fitur seperti keranjang, checkout, dan riwayat pesanan.

3.	Sistem harus menyediakan fitur logout bagi pelanggan untuk keluar dari sesi akun.
    Penjelasan: Pelanggan dapat keluar dari sesi akunnya dengan mengklik tombol logout.

4.	Sistem harus menampilkan halaman dashboard yang berisi statistik riwayat pesanan dan status pesanan.
    Penjelasan: Setelah login, pelanggan dapat melihat ringkasan profil dan daftar status pesanan terbaru.

5.	Sistem harus menyediakan fitur bagi pelanggan untuk mencari produk berdasarkan nama, kategori, dan harga pada halaman katalog.
    Penjelasan: Pelanggan dapat mencari produk yang diinginkan dengan mengetik kata kunci, memilih kategori, atau mengurutkan berdasarkan harga termurah atau termahal.

6.	Sistem harus menyediakan fitur bagi pelanggan untuk melihat detail produk yang menampilkan galeri gambar, pemilihan varian, deskripsi, harga, stok, serta ulasan dan rating.
    Penjelasan: Pelanggan dapat melihat informasi lengkap produk termasuk foto, pilihan warna/ukuran, keterangan barang, harga, ketersediaan stok, dan ulasan dari pembeli lain.

7.	Sistem harus menyediakan fitur bagi pelanggan untuk memilih varian produk (warna dan ukuran) sebelum menambahkan ke keranjang.
    Penjelasan: Sebelum memasukkan produk ke keranjang, pelanggan harus memilih varian warna dan ukuran yang tersedia.

8.	Sistem harus menyediakan fitur bagi pelanggan untuk memilih metode pembayaran, yaitu menggunakan saldo ERNA Pay atau mengunggah bukti transfer.
    Penjelasan: Pada halaman checkout, pelanggan dapat memilih membayar menggunakan saldo dompet digital ERNA Pay atau transfer manual dengan upload bukti pembayaran.

9.	Sistem harus menyediakan fitur bagi pelanggan untuk menerapkan voucher diskon, harga flash sale, dan diskon membership VIP pada saat checkout.
    Penjelasan: Pelanggan dapat memasukkan kode voucher, mendapatkan harga flash sale jika produk sedang promo, serta menikmati diskon tambahan sesuai level membership VIP.

10.	Sistem harus menyediakan fitur bagi pelanggan untuk melakukan top-up saldo ERNA Pay.
    Penjelasan: Pelanggan dapat mengisi saldo ERNA Pay dengan nominal tertentu untuk digunakan sebagai metode pembayaran.

11.	Sistem harus menampilkan halaman riwayat pesanan yang berisi daftar seluruh transaksi.
    Penjelasan: Pelanggan dapat melihat semua pesanan yang pernah dilakukan beserta status terkini masing-masing pesanan.

12.	Sistem harus menyediakan fitur bagi pelanggan untuk melacak status pesanan berdasarkan nomor ID pesanan.
    Penjelasan: Pelanggan dapat memasukkan nomor ID pesanan untuk mengetahui posisi dan status terbaru dari pesanannya.

13.	Sistem harus menyediakan fitur bagi pelanggan untuk mengonfirmasi pesanan yang telah diterima.
    Penjelasan: Setelah barang diterima, pelanggan dapat mengklik tombol konfirmasi terima untuk menandai pesanan selesai.

14.	Sistem harus menyediakan fitur bagi pelanggan untuk mengajukan permintaan retur beserta unggahan bukti foto.
    Penjelasan: Jika barang tidak sesuai, pelanggan dapat mengajukan retur dengan menyertakan foto bukti sebagai lampiran.

15.	Sistem harus menyediakan fitur bagi pelanggan untuk mengelola profil, meliputi mengubah nama, email, alamat, nomor rumah, dan foto profil.
    Penjelasan: Pelanggan dapat memperbarui data diri seperti nama, email, alamat pengiriman, nomor rumah, dan foto profil.

16.	Sistem harus menyediakan fitur bagi pelanggan untuk membeli paket membership VIP (SILVER dan GOLD) menggunakan saldo ERNA Pay.
    Penjelasan: Pelanggan dapat meningkatkan level akun menjadi SILVER atau GOLD dengan membayar menggunakan saldo ERNA Pay untuk mendapatkan benefit khusus.

17.	Sistem harus menyediakan fitur bagi pelanggan untuk menambah dan menghapus produk ke dalam wishlist.
    Penjelasan: Pelanggan dapat menyimpan produk favorit ke dalam daftar wishlist dan menghapusnya kapan saja.

18.	Sistem harus menyediakan fitur bagi pelanggan untuk melihat pratinjau voucher yang tersedia beserta ketentuan penggunaannya.
    Penjelasan: Pelanggan dapat melihat daftar voucher diskon yang tersedia, besaran diskon, minimal belanja, dan masa berlaku masing-masing voucher.

19.	Sistem harus menampilkan notifikasi secara real-time kepada pelanggan apabila terdapat perubahan status pesanan, meskipun pelanggan sedang membuka halaman lain.
    Penjelasan: Pelanggan akan mendapat pemberitahuan otomatis melalui lonceng notifikasi setiap kali status pesanan berubah tanpa perlu me-refresh halaman.

20.	Sistem harus menyediakan fitur bagi pelanggan untuk mengakses halaman informasi yang berisi panduan pemesanan, panduan ukuran, kebijakan pengembalian, syarat dan ketentuan, serta bantuan dan FAQ.
    Penjelasan: Pelanggan dapat membaca informasi seputar cara belanja, panduan ukuran pakaian, kebijakan retur, syarat dan ketentuan toko, serta pertanyaan yang sering diajukan.
