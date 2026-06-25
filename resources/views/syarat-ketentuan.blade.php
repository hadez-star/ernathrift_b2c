@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Syarat & Ketentuan' }} | {{ $nama_toko }} Premium</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* 1. VARIABEL TEMA GELAP & TERANG */
        :root {
            --gold: #D4AF37;
            --gold-hover: #bda038;
            --bg-dark: #0f0f0f;
            --bg-surface: #1a1a1a;
            --text-main: #f5f5f5;
            --text-muted: #888888;
            --border-color: #2a2a2a;
        }

        body.light-mode {
            --bg-dark: #f5f5f5;
            --bg-surface: #ffffff;
            --text-main: #111111;
            --text-muted: #666666;
            --border-color: #dddddd;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        /* Latar Belakang Gradasi Dinamis */
        body:not(.light-mode) { background: radial-gradient(circle at center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at center, #ffffff 0%, var(--bg-dark) 100%); }

        .info-container {
            width: 100%;
            max-width: 850px;
            animation: eleganceIn 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes eleganceIn {
            to { transform: translateY(0); opacity: 1; }
        }

        /* 2. KOTAK KONTEN MENGGUNAKAN VARIABEL (--bg-surface) */
        .info-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 60px 50px;
            position: relative;
            transition: 0.4s ease;
        }

        /* Bayangan berubah sesuai tema */
        body:not(.light-mode) .info-card { box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 0 20px rgba(212, 175, 55, 0.02); border-color: rgba(212, 175, 55, 0.15); }
        body.light-mode .info-card { box-shadow: 0 20px 50px rgba(0,0,0,0.05); }

        /* Tombol Kembali */
        .back-btn {
            position: absolute;
            top: 40px;
            left: 40px;
            color: var(--text-muted);
            font-size: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(136, 136, 136, 0.05);
            border: 1px solid var(--border-color);
        }

        .back-btn:hover {
            color: var(--gold);
            background: rgba(212, 175, 55, 0.1);
            border-color: rgba(212, 175, 55, 0.3);
            transform: translateX(-5px);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
            transition: 0.4s ease;
        }

        .header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* 3. TEKS MENGGUNAKAN VARIABEL (--text-main & --text-muted) */
        .content-body p {
            font-size: 14px;
            line-height: 1.8;
            color: var(--text-muted);
            margin-bottom: 20px;
            text-align: justify;
            transition: 0.4s ease;
        }

        .content-body h3 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: var(--gold);
            margin: 40px 0 15px;
            font-weight: 600;
            letter-spacing: 1px;
            transition: 0.4s ease;
        }
        
        .content-body i {
            color: var(--text-main);
            font-style: italic;
            transition: 0.4s ease;
        }

        .footer-note {
            margin-top: 50px;
            padding-top: 25px;
            border-top: 1px dashed var(--border-color);
            text-align: center;
            font-size: 12px;
            color: var(--text-muted);
            letter-spacing: 1px;
            transition: 0.4s ease;
        }

        @media (max-width: 768px) {
            .info-card { padding: 50px 25px; }
            .back-btn { top: 20px; left: 20px; }
            .header h1 { font-size: 24px; margin-top: 15px; }
            .content-body p { text-align: left; }
        }
    </style>
</head>
<body>

    <!-- SCRIPT PENGINGAT TEMA -->
    <script>
        if (localStorage.getItem('erna_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>
    <!-- ======================= -->

    <div class="info-container">
        <div class="info-card">
            <a href="javascript:history.back()" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="header">
                <h1>{{ $title ?? 'Syarat & Ketentuan' }}</h1>
            </div>

            <div class="content-body">
                <p>Selamat datang di ERNA Thrifting. Dengan mengakses, mendaftar akun, dan/atau melakukan pembelian di <i>website</i> ERNA Thrifting, Anda dianggap telah membaca, mengerti, memahami, dan menyetujui semua syarat dan ketentuan di bawah ini.</p>

                <h3>1. Akun & Keamanan Data</h3>
                <p>Pengguna diwajibkan memberikan data informasi yang akurat (nama, alamat pengiriman, nomor HP aktif) saat mendaftar atau saat melakukan proses pembaruan profil. ERNA Thrifting menjamin kerahasiaan data pribadi pengguna dan tidak akan memperjualbelikannya kepada pihak ketiga manapun. Pengguna bertanggung jawab penuh atas keamanan kata sandi (password) akun masing-masing.</p>

                <h3>2. Sistem "Siapa Cepat Dia Dapat"</h3>
                <p>Mengingat semua produk di ERNA Thrifting adalah barang tunggal (hanya 1 stok), maka sistem kami menganut asas "Siapa Cepat Pembayarannya, Dia Dapat". Memasukkan barang ke dalam keranjang belum menjamin barang tersebut menjadi milik Anda, sampai Anda berhasil menyelesaikan proses <i>Checkout</i>.</p>

                <h3>3. Saldo ERNA Pay & Membership</h3>
                <p>Saldo ERNA Pay yang telah di-<i>top up</i> tidak dapat diuangkan kembali (<i>refund</i>) ke rekening bank pribadi, dan hanya dapat digunakan untuk transaksi pembelian produk atau <i>upgrade</i> ke Membership VIP di dalam platform ERNA Thrifting. Keanggotaan VIP (Silver & Gold) bersifat permanen kecuali terdapat pelanggaran berat yang menyebabkan akun diblokir.</p>

                <h3>4. Pengiriman & Keterlambatan Ekspedisi</h3>
                <p>ERNA Thrifting bertanggung jawab mengemas barang dengan aman dan menyerahkannya kepada pihak jasa kirim maksimal 1x24 jam (di hari kerja). Setelah barang berada di tangan kurir ekspedisi, maka segala bentuk keterlambatan, kerusakan kemasan luar, atau kehilangan barang selama perjalanan adalah sepenuhnya tanggung jawab pihak ekspedisi terkait. Namun, kami akan membantu melacak dan melaporkan kendala tersebut.</p>

                <h3>5. Perubahan Syarat</h3>
                <p>ERNA Thrifting berhak mengubah sebagian atau seluruh isi Syarat & Ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Pengguna disarankan untuk membaca halaman ini secara berkala.</p>
            </div>

            <div class="footer-note">
                Terakhir diperbarui: {{ date('F Y') }} &copy; {{ $nama_toko ?? 'ERNA Thrifting' }}
            </div>
        </div>
    </div>

</body>
</html>