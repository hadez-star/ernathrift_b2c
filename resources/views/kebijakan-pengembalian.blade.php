@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Kebijakan Pengembalian' }} | {{ $nama_toko }} Premium</title>
    
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

        /* --- STYLING KONTEN KHUSUS KEBIJAKAN PENGEMBALIAN --- */
        .alert-box {
            background: rgba(217, 83, 79, 0.05);
            border: 1px solid rgba(217, 83, 79, 0.3);
            color: #e74c3c;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 35px;
            font-weight: 500;
            line-height: 1.6;
            font-size: 13px;
            display: flex;
            align-items: flex-start;
            gap: 15px;
            transition: 0.4s ease;
        }
        
        .alert-box i { 
            font-size: 20px; 
            margin-top: 2px; 
        }

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

        .content-body strong { 
            color: var(--text-main); 
            font-weight: 600; 
            transition: 0.4s ease;
        }

        /* List Diamond Style */
        .diamond-list {
            margin-bottom: 20px;
            list-style: none;
        }

        .diamond-list li {
            position: relative;
            padding-left: 30px;
            margin-bottom: 15px;
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.7;
            transition: 0.4s ease;
        }

        .diamond-list li::before {
            content: "\f3a5"; /* Icon Gem/Diamond FontAwesome */
            font-family: "Font Awesome 5 Free";
            font-weight: 400;
            position: absolute;
            left: 0;
            top: 2px;
            color: var(--gold);
            font-size: 12px;
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
            .header h1 { font-size: 20px; margin-top: 15px; }
            .alert-box { flex-direction: column; align-items: center; text-align: center; }
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
                <h1>{{ $title ?? 'Kebijakan Pengembalian' }}</h1>
            </div>

            <div class="content-body">
                
                <div class="alert-box">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        Karena sifat produk <i>thrifting</i> (bekas pakai), secara umum kami <strong>TIDAK MENERIMA</strong> pengembalian barang (Return) atau pengembalian dana (Refund) dengan alasan kebesaran, kekecilan, atau berubah pikiran.
                    </div>
                </div>

                <h3>1. Kondisi Produk Thrift</h3>
                <p>Setiap pakaian yang kami jual telah melalui proses kurasi dan sortir yang sangat ketat. Namun, mohon dipahami bahwa ini adalah barang <i>pre-loved</i> (bekas pakai), sehingga wajar jika terdapat jejak pemakaian normal (seperti warna sedikit turun, noda setitik yang bisa hilang dicuci, atau sedikit berbulu halus).</p>
                <p>Minus yang terlihat jelas (seperti noda permanen besar atau kancing lepas) <strong>pasti akan kami tuliskan secara transparan di deskripsi produk</strong>.</p>

                <h3>2. Syarat Retur (Pengembalian) Diizinkan</h3>
                <p>Kami memberikan garansi pengembalian 100% (berupa pengembalian ke Saldo ERNA Pay) <strong>HANYA</strong> jika terjadi kesalahan murni dari pihak kami, yaitu:</p>
                <ul class="diamond-list">
                    <li>Barang yang dikirim tertukar atau berbeda dengan foto produk yang Anda pesan.</li>
                    <li>Terdapat cacat atau kerusakan FATAL (robek besar, resleting rusak total) yang terlewat oleh tim *Quality Control* kami dan tidak disebutkan di deskripsi.</li>
                </ul>

                <h3>3. Prosedur Klaim Retur</h3>
                <ul class="diamond-list">
                    <li>Batas maksimal pengajuan komplain adalah <strong>1x24 Jam</strong> setelah status resi menunjukkan bahwa barang telah diterima.</li>
                    <li><strong>Wajib menyertakan Video Unboxing:</strong> Video direkam saat membuka paket pertama kali tanpa jeda/potongan sebagai bukti otentik. Tanpa video unboxing, mohon maaf komplain tidak dapat kami proses.</li>
                    <li>Jika retur disetujui, ongkos kirim pengembalian barang ke gudang kami akan ditanggung oleh pihak pembeli, dan dana seharga barang akan dikembalikan utuh ke Saldo ERNA Pay Anda.</li>
                </ul>
                
            </div>

            <div class="footer-note">
                Terakhir diperbarui: {{ date('F Y') }} &copy; {{ $nama_toko ?? 'ERNA Thrifting' }}
            </div>
        </div>
    </div>

</body>
</html>