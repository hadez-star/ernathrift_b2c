@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
    $email_toko = $setting->email ?? 'hello@ernathrifting.com';
    $wa_toko = $setting->whatsapp ?? '6281234567890';
    $alamat_toko = $setting->alamat ?? 'Pontianak, Indonesia';
    $deskripsi_toko = $setting->deskripsi ?? 'Menghadirkan koleksi pakaian bekas berkualitas premium yang dikurasi dengan ketat. Kami percaya bahwa gaya yang memukau tidak harus merusak bumi.';

    // VOUCHER LOGIC
    $voucherPromo = \App\Models\Voucher::latest()->first();
    $isVoucherActive = $voucherPromo && \Carbon\Carbon::parse($voucherPromo->valid_until)->isFuture();

    // FLASH SALE LOGIC
    $isFlashSaleActive = isset($flashSale) && $flashSale && \Carbon\Carbon::parse($flashSale->end_time)->isFuture();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $nama_toko }} | Premium & Sustainable</title>
    
    <!-- SEO & Open Graph Meta Tags -->
    <meta name="description" content="{{ $deskripsi_toko }}">
    <meta name="keywords" content="thrift store, pakaian bekas premium, sustainable fashion, {{ $nama_toko }}">
    <meta name="author" content="{{ $nama_toko }}">
    
    <meta property="og:title" content="{{ $nama_toko }} | Premium & Sustainable">
    <meta property="og:description" content="{{ $deskripsi_toko }}">
    <meta property="og:image" content="{{ asset('images/logo-og.jpg') }}"> {{-- Ganti dengan path logo Anda --}}
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $nama_toko }} | Premium & Sustainable">
    <meta name="twitter:description" content="{{ $deskripsi_toko }}">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500;1,600&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root { 
            --gold: #D4AF37; 
            --gold-hover: #bda038; 
            --bg-dark: #0f0f0f; 
            --bg-surface: #1a1a1a; 
            --text-main: #f5f5f5; 
            --text-muted: #888888; 
            --border-color: #2a2a2a; 
            --overlay: rgba(15, 15, 15, 0.7); 
            --red-accent: #D9534F;
        }

        /* --- TEMA TERANG (LIGHT MODE) GLOBAL --- */
        body.light-mode {
            --bg-dark: #f5f5f5;
            --bg-surface: #ffffff;
            --text-main: #111111;
            --text-muted: #666666;
            --border-color: #dddddd;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            font-family: 'Montserrat', sans-serif; 
            background-color: var(--bg-dark); 
            color: var(--text-main); 
            overflow-x: hidden; 
            scroll-behavior: smooth; 
            transition: background-color 0.4s ease, color 0.4s ease;
            animation: fadeInPage 0.8s ease forwards;
        }

        @keyframes fadeInPage { from { opacity: 0; } to { opacity: 1; } }

        /* --- CUSTOM SCROLLBAR --- */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 10px; border: 2px solid var(--bg-dark); }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold); }

        /* --- PREMIUM SHIMMER LOADING --- */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .skeleton { 
            background: linear-gradient(90deg, var(--border-color) 25%, var(--bg-surface) 50%, var(--border-color) 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite linear;
            border-radius: 8px;
        }

        /* --- NAVBAR EKSKLUSIF --- */
        /* Navbar styles moved to component */

        .nav-links { display: flex; align-items: center; gap: 30px; flex: 1; justify-content: flex-end; }
        .nav-item { color: var(--text-main); text-decoration: none; font-size: 11px; font-weight: 600; letter-spacing: 2px; transition: 0.3s; cursor: pointer; text-transform: uppercase; }
        .nav-item:hover { color: var(--gold) !important; }

        /* DROPDOWN */
        .nav-dropdown { position: relative; padding: 10px 0; }
        .dropdown-menu { visibility: hidden; opacity: 0; position: absolute; top: 100%; right: 0; background: var(--bg-surface); min-width: 200px; padding: 15px 0; box-shadow: 0 15px 40px rgba(0,0,0,0.1); border-radius: 8px; border: 1px solid var(--border-color); transition: 0.3s; z-index: 100; transform: translateY(10px); }
        .nav-dropdown:hover .dropdown-menu { visibility: visible; opacity: 1; transform: translateY(0); }
        .dropdown-item { display: block; padding: 10px 20px; color: var(--text-main); text-decoration: none; font-size: 11px; font-weight: 500; transition: 0.3s; letter-spacing: 1px; }
        .dropdown-item:hover { background: rgba(212, 175, 55, 0.05); color: var(--gold); padding-left: 25px; }

        .cart-wrapper { position: relative; cursor: pointer; text-decoration: none; font-size: 16px;}
        .cart-badge { position: absolute; top: -6px; right: -12px; background: var(--gold); color: #fff; font-size: 9px; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700; }

        /* --- HERO SECTION DINAMIS (DARK/LIGHT) --- */
        .hero { 
            height: 100vh; 
            display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; 
            transition: background 0.4s ease, color 0.4s ease; 
            position: relative; overflow: hidden;
        }

        .hero::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background-size: cover !important; background-position: center !important; background-attachment: fixed !important;
            z-index: -1; transition: 0.8s ease;
            animation: heroZoom 30s infinite alternate ease-in-out;
        }

        @keyframes heroZoom { from { transform: scale(1); } to { transform: scale(1.1); } }

        body:not(.light-mode) .hero::before {
            background: linear-gradient(rgba(15, 15, 15, 0.4), rgba(15, 15, 15, 0.8)), url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop');
        }
        body.light-mode .hero::before {
            background: linear-gradient(rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.4)), url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop');
        }

        /* Hapus background lama di .hero */
        body:not(.light-mode) .hero, body.light-mode .hero { background: none !important; }

        .hero-subtitle { font-size: 12px; letter-spacing: 6px; margin-bottom: 20px; text-transform: uppercase; color: var(--gold); font-weight: 600; }
        body:not(.light-mode) .hero-subtitle { text-shadow: 0 0 10px rgba(0,0,0,0.8); } /* Bayangan untuk mode gelap */
        body.light-mode .hero-subtitle { text-shadow: 0 0 10px rgba(255,255,255,0.4); }
        
        /* Bayangan pada teks agar tetap terbaca meski background lebih terang/jelas */
        .hero-title { font-family: 'Playfair Display', serif; font-size: 70px; margin-bottom: 40px; line-height: 1.1; font-weight: 700; letter-spacing: -1px; }
        body:not(.light-mode) .hero-title { text-shadow: 0 5px 15px rgba(0,0,0,0.7); } /* Bayangan hitam untuk mode gelap */
        body.light-mode .hero-title { text-shadow: 0 0 15px rgba(255,255,255,0.9), 0 0 5px rgba(255,255,255,0.8); }

        .hero-desc { max-width: 600px; margin-bottom: 40px; font-size: 14px; line-height: 1.8; transition: color 0.4s ease; font-weight: 600;}
        body:not(.light-mode) .hero-desc { color: rgba(255,255,255,0.9); text-shadow: 0 2px 10px rgba(0,0,0,0.8); } /* Bayangan hitam untuk mode gelap */
        body.light-mode .hero-desc { color: #111111; text-shadow: 0 0 15px rgba(255,255,255,1), 0 0 5px rgba(255,255,255,1); }

        .btn-jelajahi { padding: 15px 40px; background: var(--gold); border: 1px solid var(--gold); color: #111; font-size: 12px; font-weight: 700; letter-spacing: 2px; text-decoration: none; transition: 0.4s; border-radius: 4px; text-transform: uppercase; }
        .btn-jelajahi:hover { background: transparent; color: var(--gold); }
        body.light-mode .btn-jelajahi:hover { background: rgba(255,255,255,0.8); } /* Trik agar hover di light mode tidak transparan tembus */

        /* --- FLASH SALE (DINAMIS) --- */
        .flash-sale-section { border-bottom: 1px solid var(--border-color); padding: 70px 20px; text-align: center; transition: 0.4s ease; }
        body:not(.light-mode) .flash-sale-section { background: radial-gradient(circle at center, #1f1212 0%, var(--bg-dark) 100%); }
        body.light-mode .flash-sale-section { background: radial-gradient(circle at center, #fff3f3 0%, var(--bg-dark) 100%); }
        
        .fs-icon { width: 60px; height: 60px; background: rgba(217, 83, 79, 0.1); color: var(--red-accent); border: 1px solid rgba(217, 83, 79, 0.3); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 20px; box-shadow: 0 0 20px rgba(217, 83, 79, 0.2); }
        .btn-fs { display: inline-block; padding: 14px 35px; background: transparent; color: var(--gold); border: 1px solid var(--gold); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; text-decoration: none; border-radius: 30px; transition: 0.3s; }
        .btn-fs:hover { background: var(--gold); color: #111; box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3); }

        /* --- PRODUCT GRID PREMIUM --- */
        .preview-section { padding: 100px 20px; text-align: center; max-width: 1200px; margin: 0 auto; }
        .section-tag { color: var(--text-muted); font-size: 11px; font-weight: 600; letter-spacing: 4px; margin-bottom: 15px; display: block; text-transform: uppercase; }
        .section-title { font-family: 'Playfair Display', serif; font-size: 36px; margin-bottom: 50px; color: var(--gold); }
        
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 30px; text-align: left; }

        .product-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: flex; flex-direction: column; position: relative;
        }
        body:not(.light-mode) .product-card:hover { border-color: rgba(212, 175, 55, 0.5); box-shadow: 0 20px 40px rgba(0,0,0,0.8), 0 0 20px rgba(212, 175, 55, 0.1); transform: translateY(-10px); }
        body.light-mode .product-card:hover { border-color: rgba(212, 175, 55, 0.5); box-shadow: 0 20px 40px rgba(0,0,0,0.05), 0 0 20px rgba(212, 175, 55, 0.05); transform: translateY(-10px); }

        .product-img-wrap { position: relative; width: 100%; padding-top: 125%; background: var(--border-color); overflow: hidden; }
        .product-img-wrap::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(90deg, var(--border-color) 25%, var(--bg-surface) 50%, var(--border-color) 75%); background-size: 200% 100%; animation: shimmer 2s infinite linear; z-index: 1; }
        .product-img-wrap img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; filter: brightness(0.9); transition: transform 0.8s cubic-bezier(0.2, 0, 0.2, 1), filter 0.5s ease; z-index: 2; opacity: 0; }
        .product-img-wrap img.loaded { opacity: 1; }
        .product-card:hover .product-img-wrap img { filter: brightness(1.05); transform: scale(1.1); }
        .product-img-wrap i.main-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 60px; color: var(--text-muted); }

        .product-overlay {
            position: absolute; bottom: 0; left: 0; width: 100%; padding: 25px 10px 15px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            display: flex; justify-content: center; align-items: center; gap: 10px;
            opacity: 0; transform: translateY(20px); transition: all 0.3s ease; z-index: 2;
        }
        .product-card:hover .product-overlay { opacity: 1; transform: translateY(0); }

        .action-btn { width: 35px; height: 35px; border-radius: 50%; background: rgba(26, 26, 26, 0.9); border: 1px solid rgba(255,255,255,0.1); color: #f5f5f5; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; backdrop-filter: blur(5px); }
        .action-btn:hover { background: var(--gold); color: #111; border-color: var(--gold); transform: scale(1.1); }
        .share-btn:hover { background: #3498db; color: #fff; border-color: #3498db; }
        .wishlist-btn:hover, .wishlist-btn.active { background: var(--red-accent); color: #fff; border-color: var(--red-accent); }

        /* --- WISHLIST FLOATING BUTTON (TOP RIGHT) --- */
        .wishlist-btn-top {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(15, 15, 15, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        body.light-mode .wishlist-btn-top {
            background: rgba(255, 255, 255, 0.8);
            border-color: rgba(0, 0, 0, 0.08);
            color: #111;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .wishlist-btn-top:hover {
            transform: scale(1.1);
            background: var(--red-accent);
            color: #fff;
            border-color: var(--red-accent);
        }
        .wishlist-btn-top.active {
            background: rgba(217, 83, 79, 0.15);
            color: var(--red-accent) !important;
            border-color: rgba(217, 83, 79, 0.3);
        }
        .wishlist-btn-top.active i {
            color: var(--red-accent) !important;
        }
        body.light-mode .wishlist-btn-top.active {
            background: rgba(217, 83, 79, 0.1);
        }
        @media (max-width: 576px) {
            .wishlist-btn-top {
                width: 28px;
                height: 28px;
                top: 8px;
                right: 8px;
                font-size: 11px;
            }
        }

        .buy-btn { background: var(--gold); color: #111; border: none; padding: 9px 20px; border-radius: 20px; font-weight: 700; font-size: 11px; letter-spacing: 1px; text-decoration: none; transition: 0.3s; }
        .buy-btn:hover { background: var(--gold-hover); transform: scale(1.05); }

        .product-info { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; }
        .product-cat { font-size: 10px; color: var(--gold); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px; font-weight: 600; }
        .product-title { font-family: 'Playfair Display', serif; font-size: 18px; color: var(--text-main); text-decoration: none; margin-bottom: 8px; transition: 0.3s; display: block; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-title:hover { color: var(--gold); }
        .product-desc-preview { font-size: 12px; color: var(--text-muted); line-height: 1.5; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-price { font-size: 16px; color: var(--text-muted); margin-top: auto; font-weight: 500; }

        /* --- PROMO BANNER (DINAMIS) --- */
        .promo-banner { border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); padding: 80px 20px; text-align: center; transition: 0.4s ease; }
        body:not(.light-mode) .promo-banner { background: radial-gradient(circle at center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode .promo-banner { background: radial-gradient(circle at center, #fffbf5 0%, var(--bg-dark) 100%); }
        
        .promo-title { font-family: 'Playfair Display', serif; font-size: 36px; font-weight: 700; margin-bottom: 15px; color: var(--gold); }
        .countdown-timer { display: flex; justify-content: center; gap: 20px; margin-bottom: 40px; }
        
        .time-box { padding: 15px 20px; border-radius: 8px; min-width: 85px; border: 1px solid var(--border-color); transition: 0.4s ease;}
        body:not(.light-mode) .time-box { background: rgba(0, 0, 0, 0.2); }
        body.light-mode .time-box { background: rgba(0, 0, 0, 0.05); }
        
        .time-number { font-size: 28px; font-weight: 700; display: block; font-family: 'Playfair Display', serif; color: var(--text-main); }
        .voucher-box { display: inline-flex; align-items: center; border: 1px dashed var(--gold); padding: 6px 6px 6px 25px; border-radius: 30px; gap: 25px; background: rgba(212, 175, 55, 0.05); }
        .btn-copy { background: var(--gold); color: #111; border: none; padding: 12px 25px; border-radius: 25px; font-size: 10px; font-weight: 700; text-transform: uppercase; cursor: pointer; transition: 0.3s; }
        .btn-copy:hover { background: var(--gold-hover); }

        /* LIVE SEARCH STYLES */
        .search-results-dropdown {
            position: absolute; top: calc(100% + 10px); left: 0; width: 100%; 
            background: var(--bg-surface); border: 1px solid var(--border-color);
            border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 1000; display: none; overflow: hidden;
        }
        .search-result-item {
            display: flex; align-items: center; gap: 15px; padding: 12px 15px;
            text-decoration: none; color: var(--text-main); transition: 0.3s;
            border-bottom: 1px solid var(--border-color);
        }
        .search-result-item:last-child { border-bottom: none; }
        .search-result-item:hover { background: rgba(212, 175, 55, 0.1); }
        .search-result-img { width: 40px; height: 40px; object-fit: cover; border-radius: 6px; }
        .search-result-info { display: flex; flex-direction: column; gap: 2px; }
        .search-result-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .search-result-price { font-size: 11px; color: var(--gold); font-weight: 700; }

        /* --- FEATURES --- */
        .features-banner { background-color: var(--bg-surface); padding: 80px 20px; border-top: 1px solid var(--border-color); transition: 0.4s ease; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; max-width: 1100px; margin: 0 auto; text-align: center; }
        .feature-icon { font-size: 32px; color: var(--gold); margin-bottom: 20px; }
        .features-grid h3 { font-size: 13px; letter-spacing: 1px; margin-bottom: 10px; color: var(--text-main); text-transform: uppercase;}
        .features-grid p { font-size: 12px; color: var(--text-muted); line-height: 1.6; }

        @media (max-width: 992px) {
            .navbar { padding: 15px 30px; }
            .search-navbar-wrapper { flex: 1; margin: 0 20px; }
        }

        @media (max-width: 768px) {
            .navbar { flex-wrap: wrap; height: auto; padding: 15px 20px; }
            .navbar-brand { flex: 1; font-size: 18px; }
            .nav-links { flex: 1; gap: 15px; }
            .search-navbar-wrapper { order: 3; width: 100%; flex: none; margin: 15px 0 0; }
            .search-navbar { max-width: 100%; }
            .hero-title { font-size: 40px; }
            .section-title { font-size: 28px; }
        }

        /* --- FOOTER --- */
        .footer-contact i { margin-right: 12px; color: var(--gold); }


        @media (max-width: 900px) { .navbar { padding: 20px 30px; } .search-navbar-wrapper { display: none; } .hero-title { font-size: 45px; } .footer-grid { grid-template-columns: 1fr 1fr; } }
        
        .premium-swal-popup { background: var(--bg-surface) !important; border: 1px solid var(--border-color) !important; border-radius: 12px !important; color: var(--text-main) !important; }
        .premium-swal-title { color: #D4AF37 !important; font-family: 'Playfair Display', serif !important; }
        .ecommerce-toast { border-radius: 12px !important; padding: 10px 20px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.8) !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }
        body.light-mode .ecommerce-toast { box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }

        /* Testimonial CSS */
        .testimonials-section { background-color: var(--bg-dark); padding: 100px 20px; text-align: center; border-top: 1px solid var(--border-color); }
        .testimonial-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto; margin-top: 50px; }
        .testimonial-card { background: var(--bg-surface); padding: 40px; border-radius: 20px; border: 1px solid var(--border-color); transition: 0.4s; position: relative; }
        .testimonial-card:hover { transform: translateY(-10px); border-color: var(--gold); }
        .testimonial-user { font-weight: 700; color: var(--gold); font-size: 14px; margin-bottom: 5px; display: block; }
        .testimonial-product { font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: block; }
        .testimonial-text { font-family: 'Playfair Display', serif; font-size: 16px; font-style: italic; color: var(--text-main); line-height: 1.8; margin-bottom: 20px; }
        .testimonial-stars { color: var(--gold); font-size: 12px; }

        /* --- BACK TO TOP --- */
        .back-to-top.show { opacity: 1; visibility: visible; }
        .back-to-top:hover { background: var(--gold); color: #111; border-color: var(--gold); transform: translateY(-5px); }

        /* --- MOBILE E-COMMERCE GRID (SHOPSTYLE) --- */
        @media (max-width: 576px) {
            .hero-title { font-size: 32px; margin-bottom: 25px; }
            .hero-subtitle { font-size: 9px; letter-spacing: 3px; }
            .hero-desc { font-size: 11px; padding: 0 20px; }
            
            .section-title { font-size: 24px; margin-bottom: 30px; }
            .preview-section { padding: 60px 15px; }

            .product-grid { 
                grid-template-columns: 1fr 1fr; 
                gap: 10px; 
            }
            
            .product-card { 
                border-radius: 8px; 
            }
            
            .product-info { 
                padding: 10px; 
            }
            
            .product-cat { font-size: 8px; margin-bottom: 4px; }
            .product-title { 
                font-size: 12px; 
                line-height: 1.4;
                height: 34px;
                margin-bottom: 8px;
            }
            
            .product-desc-preview { display: none; }
            
            .product-price { 
                font-size: 14px; 
                font-weight: 700; 
                color: var(--gold); 
                margin-top: auto;
            }

            .product-overlay {
                opacity: 1;
                transform: translateY(0);
                padding: 10px 5px;
                background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
                gap: 5px;
            }

            .action-btn { width: 28px; height: 28px; font-size: 11px; }
            .buy-btn { padding: 6px 12px; font-size: 9px; }

            .ecommerce-toast {
                margin-bottom: 25px !important;
                border-radius: 50px !important;
                font-size: 12px !important;
                width: calc(100% - 40px) !important;
                border-color: var(--gold) !important;
                box-shadow: 0 8px 25px rgba(0,0,0,0.6) !important;
            }

            /* Flash Sale Specific on Homepage */
            .flash-sale-section { padding: 50px 15px; }
            .flash-sale-section h2 { font-size: 22px !important; }
            .time-box { min-width: 50px !important; padding: 8px !important; }
            .time-number { font-size: 16px !important; }
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

    <x-navbar :showSearch="true" />

    <section class="hero">
        <span class="hero-subtitle" data-aos="fade-up" data-aos-delay="100">Bespoke & Thrift</span>
        <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">{{ $nama_toko }}</h1>
        <p class="hero-desc" data-aos="fade-up" data-aos-delay="300">Temukan mahakarya fashion berkelanjutan. Kurasi eksklusif untuk gaya hidup yang lebih bermakna.</p>
        <div data-aos="zoom-in" data-aos-delay="400">
            <a href="#koleksi" class="btn-jelajahi">Lihat Koleksi</a>
        </div>
    </section>

    @if($isFlashSaleActive && $flashSale->is_active)
    <section class="flash-sale-section" id="flash-sale">
        <div style="max-width: 1200px; margin: 0 auto;" data-aos="fade-up">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; text-align: left; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 10px;">
                        <div class="fs-icon" style="margin: 0; width: 40px; height: 40px; font-size: 18px;"><i class="fas fa-bolt"></i></div>
                        <h2 style="font-family: 'Playfair Display', serif; font-size: 32px; color: var(--gold);">{{ $flashSale->nama_kampanye ?? 'Flash Sale Spesial' }}</h2>
                    </div>
                    <p style="color: var(--text-muted); font-size: 13px;">Berakhir dalam:</p>
                </div>
                
                <div class="countdown-timer" id="fs-timer" style="margin: 0;">
                    <div class="time-box" style="min-width: 60px; padding: 10px;"><span class="time-number" id="fs-hours" style="font-size: 20px;">00</span><span style="font-size: 8px; color: var(--text-muted); text-transform: uppercase;">Jam</span></div>
                    <div class="time-box" style="min-width: 60px; padding: 10px;"><span class="time-number" id="fs-mins" style="font-size: 20px;">00</span><span style="font-size: 8px; color: var(--text-muted); text-transform: uppercase;">Menit</span></div>
                    <div class="time-box" style="min-width: 60px; padding: 10px;"><span class="time-number" id="fs-secs" style="font-size: 20px;">00</span><span style="font-size: 8px; color: var(--text-muted); text-transform: uppercase;">Detik</span></div>
                </div>
            </div>

            <div class="product-grid">
                @foreach($flashSaleProducts as $item)
                @php $p = $item->product; @endphp
                @if($p)
                <div class="product-card" data-aos="fade-up">
                    <div class="product-img-wrap">
                        <a href="{{ url('/produk/detail/'.$p->id) }}">
                            @if($p->gambar)
                                <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama_produk }}">
                            @else
                                <i class="fas fa-tshirt main-icon"></i>
                            @endif
                        </a>
                        <div style="position: absolute; top: 15px; left: 15px; background: #E84C3D; color: white; padding: 5px 12px; border-radius: 4px; font-size: 10px; font-weight: 800; z-index: 2; box-shadow: 0 4px 10px rgba(232, 76, 61, 0.3);">
                            FLASH SALE
                        </div>
                        <div class="product-overlay">
                             <a href="{{ url('/beli-sekarang/'.$p->id) }}" class="buy-btn">BELI</a>
                             <button type="button" class="action-btn" onclick="addToCart(event, {{ $p->id }})" title="Tambah ke Keranjang">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                    <div class="product-info">
                        <div class="product-cat">{{ $p->kategori }}</div>
                        <a href="{{ url('/produk/detail/'.$p->id) }}" class="product-title">{{ $p->nama_produk }}</a>
                        <div style="display: flex; align-items: center; gap: 10px; margin-top: auto;">
                            <div class="product-price" style="color: #E84C3D; font-weight: 800; font-size: 18px;">Rp {{ number_format($item->harga_diskon, 0, ',', '.') }}</div>
                            <div style="text-decoration: line-through; color: var(--text-muted); font-size: 12px;">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                        </div>
                        <div style="margin-top: 15px;">
                            <div style="display: flex; justify-content: space-between; font-size: 10px; margin-bottom: 5px; color: var(--text-muted); font-weight: 600;">
                                <span>TERSEDIA: {{ $item->kuota_stok }}</span>
                                <span>TERJUAL: 0%</span>
                            </div>
                            <div style="height: 6px; background: var(--border-color); border-radius: 10px; overflow: hidden;">
                                <div style="width: 10%; height: 100%; background: linear-gradient(to right, #E84C3D, #ff7675); border-radius: 10px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
            
            <div style="margin-top: 50px;">
                <a href="{{ url('/katalog/flash-sale') }}" class="btn-fs">Lihat Semua Promo Flash Sale</a>
            </div>
        </div>
    </section>
    @endif

    <section class="preview-section" id="koleksi">
        <span class="section-tag" data-aos="fade-up">FEATURED CURATED PIECES</span>
        <h2 class="section-title" data-aos="fade-up">Koleksi Eksklusif</h2>
        
        <div class="product-grid">
            @forelse($featuredProducts as $p)
                <div class="product-card" data-aos="fade-up">
                    <div class="product-img-wrap">
                        <a href="{{ url('/produk/detail/'.$p->id) }}">
                            @if($p->gambar)
                                <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama_produk }}">
                            @else
                                <i class="fas fa-tshirt main-icon"></i>
                            @endif
                        </a>

                        <button type="button" class="wishlist-btn-top {{ \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $p->id)->exists() ? 'active' : '' }}" onclick="toggleWishlist(event, {{ $p->id }})" title="Simpan ke Wishlist">
                            <i class="{{ \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $p->id)->exists() ? 'fas' : 'far' }} fa-heart" 
                               style="{{ \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $p->id)->exists() ? 'color: var(--red-accent);' : '' }}"></i>
                        </button>
                        
                        <div class="product-overlay">
                            <button type="button" class="action-btn share-btn" onclick="shareProduct('{{ url('/produk/detail/' . $p->id) }}', '{{ addslashes($p->nama_produk) }}')" title="Bagikan">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            
                            <a href="{{ url('/beli-sekarang/'.$p->id) }}" class="buy-btn">BELI</a>
                            
                            <button type="button" class="action-btn" onclick="addToCart(event, {{ $p->id }})" title="Tambah ke Keranjang">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="product-info">
                        <div class="product-cat">{{ $p->kategori ?? 'Koleksi' }}</div>
                        <a href="{{ url('/produk/detail/'.$p->id) }}" class="product-title">
                            {{ $p->nama_produk }}
                        </a>
                        
                        <div class="product-desc-preview">
                            {{ \Illuminate\Support\Str::limit(strip_tags($p->deskripsi ?? 'Pakaian thrift eksklusif berkualitas premium.'), 60, '...') }}
                        </div>
                        
                        <div class="product-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; padding: 50px; text-align: center; color: var(--text-muted); border: 1px dashed var(--border-color); border-radius: 12px;">Belum ada koleksi eksklusif minggu ini.</div>
            @endforelse
        </div>
    </section>

    @if($isVoucherActive)
    <section class="promo-banner" data-aos="fade-up" id="voucher-section">
        <h2 class="promo-title">Promo Eksklusif</h2>
        <p style="margin-bottom: 40px; color: var(--text-muted); font-size: 13px;">Gunakan kode di bawah untuk mendapatkan potongan <strong>Rp {{ number_format($voucherPromo->reward_amount, 0, ',', '.') }}</strong></p>
        
        <div class="countdown-timer">
            <div class="time-box"><span class="time-number" id="v-days">00</span><span style="font-size: 9px; color: var(--text-muted); text-transform: uppercase;">Hari</span></div>
            <div class="time-box"><span class="time-number" id="v-hours">00</span><span style="font-size: 9px; color: var(--text-muted); text-transform: uppercase;">Jam</span></div>
            <div class="time-box"><span class="time-number" id="v-mins">00</span><span style="font-size: 9px; color: var(--text-muted); text-transform: uppercase;">Menit</span></div>
            <div class="time-box"><span class="time-number" id="v-secs">00</span><span style="font-size: 9px; color: var(--text-muted); text-transform: uppercase;">Detik</span></div>
        </div>

        <div class="voucher-box">
            <span style="font-weight: 700; letter-spacing: 2px; color: var(--gold);" id="voucherCode">{{ $voucherPromo->code }}</span>
            <button class="btn-copy" onclick="copyVoucher('{{ $voucherPromo->code }}')">Salin Kode</button>
        </div>
    </section>
    @endif

    <section class="features-banner">
        <div class="features-grid">
            <div data-aos="fade-up"><i class="far fa-gem feature-icon"></i><h3>KUALITAS PREMIUM</h3><p style="font-size: 12px; color: var(--text-muted);">Kurasi ketat, material terbaik.</p></div>
            <div data-aos="fade-up" data-aos-delay="100"><i class="fas fa-truck-fast feature-icon"></i><h3>PENGIRIMAN CEPAT</h3><p style="font-size: 12px; color: var(--text-muted);">Eksklusif langsung ke pintu Anda.</p></div>
            <div data-aos="fade-up" data-aos-delay="200"><i class="fas fa-shield-alt feature-icon"></i><h3>TRANSAKSI AMAN</h3><p style="font-size: 12px; color: var(--text-muted);">Pembayaran terenkripsi penuh.</p></div>
        </div>
    </section>

    @if($testimonials->count() > 0)
    <section class="testimonials-section" data-aos="fade-up">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 36px; color: var(--gold); margin-bottom: 10px;">Suara Pelanggan</h2>
        <p style="color: var(--text-muted); font-size: 13px; letter-spacing: 2px; text-transform: uppercase;">Review Asli Dari Pembeli Kami</p>
        
        <div class="testimonial-grid">
            @foreach($testimonials as $t)
            <div class="testimonial-card">
                <div class="testimonial-stars">
                    @for($i=0; $i<$t->rating; $i++) <i class="fas fa-star"></i> @endfor
                </div>
                <p class="testimonial-text">"{{ $t->komentar }}"</p>
                <span class="testimonial-user">{{ $t->user->name }}</span>
                <span class="testimonial-product">Membeli {{ $t->product?->nama_produk ?? 'Produk Dihapus' }}</span>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    <x-footer />

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });
        
        AOS.init({ duration: 1000, once: true });


        function getSwalColors() {
            return {
                bg: document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a',
                text: document.body.classList.contains('light-mode') ? '#111111' : '#ffffff'
            };
        }

        function copyVoucher(code) {
            navigator.clipboard.writeText(code);
            let c = getSwalColors();
            Swal.fire({ title: 'Disalin!', text: 'Kode voucher ' + code + ' siap digunakan.', icon: 'success', confirmButtonColor: '#D4AF37', background: c.bg, color: c.text, customClass: { popup: 'premium-swal-popup' } });
        }

        // TIMER KHUSUS VOUCHER
        @if($isVoucherActive)
            let vTimerEnd = new Date("{{ $voucherPromo->valid_until }}").getTime();
            let y = setInterval(function() {
                let now = new Date().getTime();
                let dist = vTimerEnd - now;

                if (dist < 0) {
                    clearInterval(y);
                    const vSection = document.getElementById('voucher-section');
                    if(vSection) vSection.style.display = 'none';
                } else {
                    document.getElementById("v-days").innerHTML = String(Math.floor(dist / (1000 * 60 * 60 * 24))).padStart(2, '0');
                    document.getElementById("v-hours").innerHTML = String(Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                    document.getElementById("v-mins").innerHTML = String(Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                    document.getElementById("v-secs").innerHTML = String(Math.floor((dist % (1000 * 60)) / 1000)).padStart(2, '0');
                }
            }, 1000);
        @endif

        // TIMER KHUSUS FLASH SALE
        @if($isFlashSaleActive && $flashSale->is_active)
            let fsTimerEnd = new Date("{{ $flashSale->end_time }}").getTime();
            let fsInterval = setInterval(function() {
                let now = new Date().getTime();
                let dist = fsTimerEnd - now;

                if (dist < 0) {
                    clearInterval(fsInterval);
                    const fsSection = document.getElementById('flash-sale');
                    if(fsSection) fsSection.style.display = 'none';
                } else {
                    let h = Math.floor(dist / (1000 * 60 * 60));
                    document.getElementById("fs-hours").innerHTML = String(h).padStart(2, '0');
                    document.getElementById("fs-mins").innerHTML = String(Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                    document.getElementById("fs-secs").innerHTML = String(Math.floor((dist % (1000 * 60)) / 1000)).padStart(2, '0');
                }
            }, 1000);
        @endif

        function toggleWishlist(e, productId) {
            if (e) e.preventDefault();
            const btn = e.currentTarget;
            const icon = btn.querySelector('i');
            let c = getSwalColors();

            fetch(`/wishlist/tambah/${productId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'login_required') {
                    Swal.fire({
                        title: 'Akses Ditolak', text: 'Silakan login untuk menyimpan koleksi ini.', icon: 'info', showCancelButton: true, confirmButtonText: 'Login Sekarang', confirmButtonColor: '#D4AF37', background: c.bg, color: c.text, customClass: { popup: 'premium-swal-popup' }
                    }).then((result) => { if (result.isConfirmed) window.location.href = '/login'; });
                } else {
                    if(data.status === 'added') {
                        icon.classList.replace('far', 'fas'); icon.style.color = 'var(--red-accent)';
                    } else {
                        icon.classList.replace('fas', 'far'); icon.style.color = '';
                    }
                    Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, icon: 'success', title: data.message, background: c.bg, color: c.text, customClass: { popup: 'ecommerce-toast' } });
                }
            });
        }

        function addToCart(e, productId) {
            if (e) e.preventDefault();
            let c = getSwalColors();

            fetch(`/keranjang/tambah/${productId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'login_required') {
                    Swal.fire({
                        title: 'Akses Ditolak', text: 'Silakan login untuk menambahkan ke keranjang.', icon: 'info', showCancelButton: true, confirmButtonText: 'Login Sekarang', confirmButtonColor: '#D4AF37', background: c.bg, color: c.text, customClass: { popup: 'premium-swal-popup' }
                    }).then((result) => { if (result.isConfirmed) window.location.href = '/login'; });
                } else if (data.status === 'success') {
                    let badge = document.querySelector('.cart-badge');
                    if(badge) { 
                        badge.innerText = data.cart_count; 
                        badge.style.transform = 'scale(1.5)';
                        badge.style.transition = '0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                        setTimeout(() => badge.style.transform = 'scale(1)', 300);
                    } 
                    else {
                        let cartIcon = document.querySelector('.cart-wrapper');
                        if(cartIcon) cartIcon.innerHTML += `<span class="cart-badge">${data.cart_count}</span>`;
                    }
                    Swal.fire({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, icon: 'success', title: data.message, background: c.bg, color: c.text, customClass: { popup: 'ecommerce-toast' } });
                }
            });
        }
        
        // FUNGSI SHARE PRODUK
        function shareProduct(url, title) {
            let c = getSwalColors();
            if (navigator.share) {
                navigator.share({
                    title: 'Cek koleksi eksklusif ini: ' + title,
                    text: 'Saya menemukan barang Thrift keren di ERNA Thrifting!',
                    url: url
                }).then(() => {
                    console.log('Berhasil dibagikan');
                }).catch((error) => {
                    console.log('Gagal membagikan', error);
                });
            } else {
                navigator.clipboard.writeText(url).then(() => {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Tautan produk disalin!', showConfirmButton: false, timer: 2500, background: c.bg, color: c.text, iconColor: '#D4AF37', customClass: { popup: 'ecommerce-toast' } });
                });
            }
        }

        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: window.innerWidth <= 576 ? 'bottom' : 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: 'var(--bg-surface)',
                color: 'var(--text-main)',
                customClass: {
                    popup: 'ecommerce-toast'
                },
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif
        
        function showSaldo(s) {
            let c = getSwalColors();
            let bgBox = document.body.classList.contains('light-mode') ? 'rgba(0,0,0,0.05)' : 'linear-gradient(135deg, #1f1a14, #0f0c0a)';
            Swal.fire({
                title: '<span style="color:#D4AF37; font-family:\'Playfair Display\'"><i class="fas fa-wallet" style="margin-right:10px;"></i> ERNA Pay</span>',
                html: `<div style="margin-top: 15px;"><div style="background: ${bgBox}; border: 1px solid var(--border-color); border-radius: 12px; padding: 30px 20px;"><p style="font-family: 'Montserrat'; font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px;">Total Saldo Aktif</p><h2 style="font-family: 'Playfair Display'; font-size: 36px; color: #D4AF37; margin: 0; font-weight: 700;">Rp ${s ? s.toLocaleString('id-ID') : '0'}</h2></div></div>`,
                confirmButtonText: 'Top Up Saldo', confirmButtonColor: '#D4AF37', background: c.bg, color: c.text, customClass: { popup: 'premium-swal-popup' }
            }).then((result) => { if (result.isConfirmed) { window.location.href = "{{ url('/saldo-erna-pay') }}"; } });
        }

        function showMembership(statusPaket) {
            let c = getSwalColors();
            let status = statusPaket ? statusPaket.toUpperCase() : 'REGULER';
            Swal.fire({
                title: '<span style="color:#D4AF37; font-family:\'Playfair Display\'"><i class="fas fa-crown" style="margin-right:10px;"></i> Membership VIP</span>',
                html: `<div style="margin-top: 15px;"><div style="background: linear-gradient(135deg, #D4AF37, #997a15); border-radius: 12px; padding: 30px 20px;"><p style="font-family: 'Montserrat'; font-size: 11px; color: rgba(0,0,0,0.7); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; font-weight:600;">Status Member</p><h2 style="font-family: 'Playfair Display'; font-size: 32px; color: #000; margin: 0; font-weight: 700;">${status}</h2></div></div>`,
                confirmButtonText: '<i class="fas fa-arrow-up"></i> Upgrade VIP', confirmButtonColor: '#D4AF37', background: c.bg, color: c.text, customClass: { popup: 'premium-swal-popup' }
            }).then((result) => { if (result.isConfirmed) { window.location.href = "{{ url('/membership-vip') }}"; } });
        }
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var images = document.querySelectorAll('.product-img-wrap img');
            images.forEach(function(img) {
                if (img.complete) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        img.classList.add('loaded');
                    });
                }
            });
        });
    </script>
</body>
</html>
