@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Panduan Ukuran' }} | {{ $nama_toko }} Premium</title>
    
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
            transition: background-color 0.4s ease, color 0.4s ease;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
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
            margin-bottom: 50px;
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
        .content-body h3 {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            color: var(--gold);
            margin: 35px 0 15px;
            font-weight: 600;
            transition: 0.4s ease;
        }

        .content-body p {
            font-size: 14px;
            line-height: 1.8;
            color: var(--text-muted);
            margin-bottom: 20px;
            transition: 0.4s ease;
        }

        .content-body ul {
            margin-bottom: 20px;
            list-style: none;
        }

        .content-body ul li {
            position: relative;
            padding-left: 30px;
            margin-bottom: 12px;
            font-size: 14px;
            color: var(--text-muted);
            transition: 0.4s ease;
        }

        .content-body ul li strong {
            color: var(--text-main);
            transition: 0.4s ease;
        }

        .content-body ul li::before {
            content: "\f058";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--gold);
        }

        /* --- TABLE STYLE PREMIUM DINAMIS --- */
        .table-responsive {
            overflow-x: auto;
            margin: 30px 0;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            transition: 0.4s ease;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th {
            background: rgba(212, 175, 55, 0.05);
            color: var(--gold);
            padding: 18px 15px;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid var(--gold);
        }

        td {
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-muted);
            transition: 0.4s ease;
        }

        tr:last-child td { border-bottom: none; }
        
        tr:hover td {
            color: var(--gold);
        }
        
        body:not(.light-mode) tr:hover td { background: rgba(255, 255, 255, 0.02); }
        body.light-mode tr:hover td { background: rgba(0, 0, 0, 0.02); }

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
            <a href="javascript:history.back()" class="back-btn" title="Kembali ke Halaman Sebelumnya">
                <i class="fas fa-arrow-left"></i>
            </a>

            <div class="header">
                <h1>{{ $title ?? 'Panduan Ukuran' }}</h1>
            </div>

            <div class="content-body">
                <h3>Pentingnya Mengukur Pakaian Thrift</h3>
                <p>Berbeda dengan pakaian baru di toko resmi, pakaian <i>thrift</i> berasal dari berbagai negara dan merek, sehingga standar ukuran (S, M, L, XL) pada label *tag* baju seringkali tidak akurat untuk standar tubuh orang Indonesia.</p>
                <p>Oleh karena itu, kami <strong style="color: var(--text-main);">selalu mengukur ulang setiap produk secara manual</strong>. Tolok ukur utama yang kami gunakan adalah Panjang (P) dan Lebar Dada (L).</p>

                <h3>Cara Kami Mengukur (P x L)</h3>
                <ul>
                    <li><strong>Panjang (P):</strong> Diukur dari titik tertinggi bahu (dekat kerah) lurus tegak ke bawah hingga ujung terbawah pakaian.</li>
                    <li><strong>Lebar (L):</strong> Diukur mendatar dari ujung ketiak kiri melintang ke ujung ketiak kanan. <i style="color: var(--text-muted);">(Catatan: Untuk Lingkar Dada, silakan kalikan Lebar x 2)</i>.</li>
                </ul>

                <h3>Estimasi Ukuran Standar Lokal</h3>
                <p>Sebagai referensi, berikut adalah estimasi ukuran (Lebar Dada) untuk standar lokal Indonesia:</p>
                
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Label Ukuran</th>
                                <th>Lebar Dada (L)</th>
                                <th>Lingkar Dada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Size S</td><td>46 - 48 cm</td><td>92 - 96 cm</td></tr>
                            <tr><td>Size M</td><td>49 - 51 cm</td><td>98 - 102 cm</td></tr>
                            <tr><td>Size L</td><td>52 - 54 cm</td><td>104 - 108 cm</td></tr>
                            <tr><td>Size XL</td><td>55 - 58 cm</td><td>110 - 116 cm</td></tr>
                            <tr><td>Size XXL (Oversize)</td><td>> 59 cm</td><td>> 118 cm</td></tr>
                        </tbody>
                    </table>
                </div>

                <h3>Tips Berbelanja</h3>
                <p>Kami menyarankan Anda untuk mengukur salah satu baju favorit Anda yang paling nyaman di badan dari ketiak ke ketiak. Jadikan angka tersebut sebagai patokan pasti saat berbelanja di ERNA Thrifting!</p>
            </div>

            <div class="footer-note">
                Terakhir diperbarui: {{ date('F Y') }} &copy; {{ $nama_toko ?? 'ERNA Thrifting' }}
            </div>
        </div>
    </div>

</body>
</html>