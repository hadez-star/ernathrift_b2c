@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara Pemesanan | {{ $nama_toko }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* 1. VARIABEL TEMA GELAP & TERANG */
        :root {
            --gold: #D4AF37;
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
            transition: background-color 0.4s ease, color 0.4s ease;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 50px 20px;
        }

        /* Latar Belakang Gradasi Dinamis */
        body:not(.light-mode) { background: radial-gradient(circle at center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at center, #ffffff 0%, var(--bg-dark) 100%); }

        .info-container {
            width: 100%;
            max-width: 800px;
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
            padding: 60px 80px;
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

        .page-header {
            text-align: center;
            margin-bottom: 50px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
            transition: 0.4s ease;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .content-section {
            margin-bottom: 40px;
        }

        .content-title {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: var(--text-main);
            margin-bottom: 15px;
            font-weight: 600;
            transition: 0.4s ease;
        }

        .content-text {
            font-size: 14px;
            line-height: 1.8;
            color: var(--text-muted);
            margin-bottom: 25px;
            transition: 0.4s ease;
        }

        /* Styling List Langkah-langkah */
        .steps-list {
            list-style: none;
            counter-reset: custom-counter;
        }

        .steps-list li {
            position: relative;
            padding-left: 45px;
            margin-bottom: 25px;
            font-size: 14px;
            line-height: 1.7;
            color: var(--text-muted);
            transition: 0.4s ease;
        }

        .steps-list li::before {
            counter-increment: custom-counter;
            content: counter(custom-counter);
            position: absolute;
            left: 0;
            top: -2px;
            width: 28px;
            height: 28px;
            background: rgba(212, 175, 55, 0.1);
            color: var(--gold);
            border: 1px solid rgba(212, 175, 55, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            font-family: 'Montserrat', sans-serif;
        }

        .steps-list li strong {
            color: var(--text-main);
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: 0.4s ease;
        }

        .highlight-text {
            color: var(--gold);
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .info-card { padding: 40px 30px; }
            .back-btn { top: 20px; left: 20px; }
            .page-title { font-size: 24px; margin-top: 15px; }
            .steps-list li { padding-left: 35px; }
            .steps-list li::before { width: 24px; height: 24px; font-size: 10px; }
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
            <a href="javascript:history.back()" class="back-btn" title="Kembali ke Beranda">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="page-header">
                <h1 class="page-title">Cara Pemesanan</h1>
            </div>

            <div class="content-section">
                <h2 class="content-title">Mudahnya Berbelanja di ERNA Thrifting</h2>
                <p class="content-text">
                    Kami telah mendesain sistem pemesanan yang mudah dan cepat agar Anda tidak perlu repot saat mengamankan barang incaran Anda. Ikuti langkah-langkah berikut:
                </p>

                <ol class="steps-list">
                    <li>
                        <strong>Eksplorasi Katalog:</strong> Cari produk yang Anda inginkan di halaman Beranda atau Katalog. Anda bisa menggunakan fitur pencarian untuk menemukan jenis pakaian spesifik.
                    </li>
                    <li>
                        <strong>Cek Detail Produk:</strong> Klik produk untuk melihat foto detail, minus (jika ada), dan ukuran pasti (Panjang x Lebar). Ingat, setiap barang <span class="highlight-text">hanya ada 1 stok!</span>
                    </li>
                    <li>
                        <strong>Masukkan Keranjang:</strong> Klik tombol ikon keranjang. Anda bisa melanjutkan belanja atau langsung menuju halaman Keranjang Anda di sudut kanan atas.
                    </li>
                    <li>
                        <strong>Proses Checkout:</strong> Di halaman Checkout, pastikan alamat pengiriman Anda sudah lengkap dan benar. Biaya ongkos kirim akan otomatis dihitung oleh sistem.
                    </li>
                    <li>
                        <strong>Pembayaran:</strong> Kami menggunakan sistem <span class="highlight-text">ERNA Pay</span>. Pastikan saldo Anda mencukupi. Jika kurang, silakan lakukan Top Up terlebih dahulu di menu Profil.
                    </li>
                    <li>
                        <strong>Pesanan Diproses:</strong> Setelah pembayaran berhasil, status pesanan Anda akan berubah menjadi "Dikemas". Duduk manis, dan kami akan segera mengirimkannya ke rumah Anda!
                    </li>
                </ol>
            </div>

            <div class="content-section" style="margin-bottom: 0; border-top: 1px solid var(--border-color); padding-top: 30px; transition: 0.4s ease;">
                <h2 class="content-title" style="font-size: 18px;">Kendala Saat Memesan?</h2>
                <p class="content-text" style="margin-bottom: 0;">
                    Jika Anda mengalami kesulitan saat melakukan proses <em>checkout</em> atau <em>top up</em> saldo, silakan hubungi admin kami melalui tombol WhatsApp yang ada di bagian bawah <em>website</em>.
                </p>
            </div>

        </div>
    </div>

</body>
</html>