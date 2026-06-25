@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan & FAQ | {{ $nama_toko }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* --- 1. VARIABEL WARNA ADAPTIF --- */
        :root {
            --gold: #D4AF37;
            --gold-hover: #bda038;
            --bg-dark: #0f0f0f;
            --bg-surface: #1a1a1a;
            --text-main: #f5f5f5;
            --text-muted: #888888;
            --border-color: #2a2a2a;
            --input-bg: rgba(255, 255, 255, 0.02);
        }

        /* TEMA TERANG (LIGHT MODE) */
        body.light-mode {
            --bg-dark: #f5f5f5;
            --bg-surface: #ffffff;
            --text-main: #111111;
            --text-muted: #666666;
            --border-color: #dddddd;
            --input-bg: #f9f9f9;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 60px 20px;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        /* Latar Belakang Gradasi Dinamis */
        body:not(.light-mode) { background: radial-gradient(circle at center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at center, #ffffff 0%, #f0f0f0 100%); }

        .faq-container {
            width: 100%;
            max-width: 800px;
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 50px;
            transition: 0.4s ease;
            animation: eleganceIn 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
        }

        body:not(.light-mode) .faq-container { 
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 0 20px rgba(212, 175, 55, 0.02);
            border-color: rgba(212, 175, 55, 0.15); 
        }
        body.light-mode .faq-container { 
            box-shadow: 0 20px 50px rgba(0,0,0,0.05); 
        }

        @keyframes eleganceIn { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        /* --- HEADER --- */
        .header { display: flex; align-items: center; justify-content: center; margin-bottom: 40px; position: relative; }
        .btn-back {
            position: absolute; left: 0; color: var(--text-muted); font-size: 20px; text-decoration: none; transition: 0.3s;
            width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;
            border-radius: 50%; background: rgba(136,136,136,0.05); border: 1px solid var(--border-color);
        }
        .btn-back:hover { color: var(--gold); background: rgba(212, 175, 55, 0.1); transform: translateX(-5px); }
        .header h2 { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; color: var(--gold); letter-spacing: 1px; }

        /* --- SEARCH BOX --- */
        .search-box { position: relative; margin-bottom: 40px; }
        .search-box i { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: var(--gold); opacity: 0.7; }
        .search-box input {
            width: 100%; padding: 18px 20px 18px 55px; border-radius: 12px;
            border: 1px solid var(--border-color); background: var(--input-bg);
            color: var(--text-main); font-size: 14px; font-family: 'Montserrat'; outline: none; transition: 0.3s;
        }
        .search-box input:focus { border-color: var(--gold); }

        .faq-section-title {
            font-size: 11px; font-weight: 700; color: var(--gold); text-transform: uppercase; letter-spacing: 2px;
            margin: 35px 0 15px; display: block; border-left: 3px solid var(--gold); padding-left: 15px; transition: 0.4s ease;
        }

        /* --- ACCORDION --- */
        .faq-item { border-bottom: 1px solid var(--border-color); padding: 20px 0; transition: 0.3s; }
        
        .faq-question {
            display: flex; justify-content: space-between; align-items: center; cursor: pointer;
            font-size: 15px; font-weight: 500; color: var(--text-main); transition: 0.3s;
        }
        .faq-question:hover { color: var(--gold); }
        .faq-question i { font-size: 12px; color: var(--text-muted); transition: 0.4s ease; }
        
        .faq-answer { max-height: 0; overflow: hidden; transition: all 0.4s cubic-bezier(0, 1, 0, 1); }
        .faq-answer p { padding-top: 15px; font-size: 14px; color: var(--text-muted); line-height: 1.8; font-weight: 300; transition: 0.4s ease; }
        
        .faq-item.active .faq-answer { max-height: 500px; }
        .faq-item.active .faq-question { color: var(--gold); font-weight: 600; }
        .faq-item.active .faq-question i { transform: rotate(180deg); color: var(--gold); }

        /* --- CONTACT CARD --- */
        .contact-card {
            border: 1px solid var(--border-color); border-radius: 16px; padding: 35px; margin-top: 50px; text-align: center; transition: 0.4s ease;
        }
        body:not(.light-mode) .contact-card { background: linear-gradient(135deg, rgba(212, 175, 55, 0.05), rgba(0,0,0,0.3)); border-color: rgba(212, 175, 55, 0.1); }
        body.light-mode .contact-card { background: #fdfdfd; border-color: var(--border-color); }

        .contact-card h3 { font-family: 'Playfair Display', serif; font-size: 20px; margin-bottom: 10px; color: var(--gold); }
        .contact-card p { font-size: 13px; color: var(--text-muted); margin-bottom: 25px; transition: 0.4s ease; }
        
        .btn-whatsapp {
            display: inline-flex; align-items: center; gap: 10px; background: #25D366; color: #fff;
            padding: 15px 35px; border-radius: 12px; text-decoration: none; font-weight: 700;
            font-size: 13px; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s;
        }
        .btn-whatsapp:hover { background: #1ebd5a; transform: translateY(-3px); box-shadow: 0 10px 25px rgba(37, 211, 102, 0.3); }

        @media (max-width: 600px) {
            .faq-container { padding: 40px 25px; border-radius: 0; }
            .header h2 { font-size: 22px; }
        }
    </style>
</head>
<body>

    <!-- === SKRIP PENGINGAT TEMA (SANGAT PENTING) === -->
    <script>
        if (localStorage.getItem('erna_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>

    <div class="faq-container">
        <div class="header">
            <a href="{{ url('/profile') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
            <h2>Bantuan & FAQ</h2>
        </div>

        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="faqSearch" placeholder="Cari topik bantuan... (Contoh: cara retur)">
        </div>

        <span class="faq-section-title">Seputar Produk</span>
        
        <div class="faq-item">
            <div class="faq-question">Apakah baju thrift di {{ $nama_toko }} sudah dicuci? <i class="fas fa-chevron-down"></i></div>
            <div class="faq-answer">
                <p>Tidak, semua koleksi kami belum melalui proses pembersihan (laundry) profesional dan disinfeksi uap agar aman dan siap langsung Anda gunakan silakan anda melakukan pencucian atau laundry.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">Apakah ada restock untuk barang yang sudah habis? <i class="fas fa-chevron-down"></i></div>
            <div class="faq-answer">
                <p>Barang thrift bersifat unik (one-of-a-kind). Jadi, setiap produk hanya tersedia 1 stok dan tidak akan ada restock dengan motif atau model yang identik.</p>
            </div>
        </div>

        <span class="faq-section-title">Pengiriman & Retur</span>

        <div class="faq-item">
            <div class="faq-question">Berapa lama estimasi pengiriman pesanan? <i class="fas fa-chevron-down"></i></div>
            <div class="faq-answer">
                <p>Kami memproses pengiriman maksimal 1x24 jam setelah pembayaran dikonfirmasi. Estimasi sampai biasanya 2-4 hari kerja tergantung lokasi Anda.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">Kebijakan pengembalian barang (Retur)? <i class="fas fa-chevron-down"></i></div>
            <div class="faq-answer">
                <p>Retur hanya diterima jika terjadi kesalahan kirim atau noda fatal yang tidak disebutkan di deskripsi. Wajib menyertakan video unboxing tanpa jeda saat mengajukan klaim.</p>
            </div>
        </div>

        <span class="faq-section-title">Saldo & Membership</span>

        <div class="faq-item">
            <div class="faq-question">Apa keuntungan menjadi Member VIP? <i class="fas fa-chevron-down"></i></div>
            <div class="faq-answer">
                <p>Member VIP mendapatkan keuntungan berupa Gratis Ongkir, Diskon otomatis di setiap transaksi, dan akses koleksi terbaru lebih awal dari pengguna biasa.</p>
            </div>
        </div>

        <div class="contact-card">
            <h3>Belum menemukan jawaban?</h3>
            <p>Konsultasikan langsung dengan Customer Service kami yang siap membantu Anda di jam operasional.</p>
            <a href="https://wa.me/{{ $setting->whatsapp ?? '6281234567890' }}" target="_blank" class="btn-whatsapp">
                <i class="fab fa-whatsapp"></i> Chat WhatsApp Admin
            </a>
        </div>
    </div>

    <script>
        // SCRIPT ACCORDION
        document.querySelectorAll('.faq-question').forEach(item => {
            item.addEventListener('click', () => {
                const parent = item.parentElement;
                document.querySelectorAll('.faq-item').forEach(faq => {
                    if (faq !== parent) faq.classList.remove('active');
                });
                parent.classList.toggle('active');
            });
        });

        // SCRIPT PENCARIAN
        const searchInput = document.getElementById('faqSearch');
        searchInput.addEventListener('keyup', () => {
            const filter = searchInput.value.toLowerCase();
            const items = document.querySelectorAll('.faq-item');
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(filter) ? "" : "none";
            });
        });
    </script>
</body>
</html>