@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
    $cartCount = Auth::check() ? \App\Models\Cart::where('user_id', Auth::id())->sum('jumlah') : 0;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->nama_produk ?? 'Detail Produk' }} | {{ $nama_toko }}</title>
    
    <!-- SEO & Open Graph Meta Tags -->
    @php
        $plainDesc = strip_tags($product->deskripsi);
        $shortDesc = strlen($plainDesc) > 150 ? substr($plainDesc, 0, 150) . '...' : $plainDesc;
        $ogTitle = ($product->nama_produk ?? 'Detail Produk') . ' - Rp ' . number_format($product->harga ?? 0, 0, ',', '.');
    @endphp
    <meta name="description" content="{{ $shortDesc }}">
    <meta name="keywords" content="{{ $product->kategori }}, {{ $product->nama_produk }}, thrift store, {{ $nama_toko }}">
    
    <meta property="og:title" content="{{ $ogTitle }}">
    <meta property="og:description" content="{{ $shortDesc }}">
    <meta property="og:image" content="{{ asset($product->gambar) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="product">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $ogTitle }}">
    <meta name="twitter:description" content="{{ $shortDesc }}">
    <meta name="twitter:image" content="{{ asset($product->gambar) }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --danger: #e74c3c;
            --success: #27ae60;
        }

        /* --- TEMA TERANG (LIGHT MODE) GLOBAL --- */
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
            padding: 40px 20px;
            transition: background-color 0.4s ease, color 0.4s ease;
            animation: fadeInPage 0.8s ease forwards;
        }

        @keyframes fadeInPage { from { opacity: 0; } to { opacity: 1; } }

        /* --- CUSTOM SCROLLBAR --- */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-dark); }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 10px; border: 2px solid var(--bg-dark); }
        ::-webkit-scrollbar-thumb:hover { background: var(--gold); }

        body:not(.light-mode) { background: radial-gradient(circle at top center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at top center, #ffffff 0%, var(--bg-dark) 100%); }

        .container {
            max-width: 1000px;
            width: 100%;
            animation: eleganceIn 0.8s ease forwards;
        }

        @keyframes eleganceIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .btn-back-top {
            display: inline-flex; align-items: center; gap: 10px;
            color: var(--text-muted); text-decoration: none; font-size: 11px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 2px; margin-bottom: 30px; transition: 0.3s;
        }
        .btn-back-top:hover { color: var(--gold); transform: translateX(-5px); }

        .detail-card {
            background: var(--bg-surface);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            display: flex;
            overflow: hidden;
            transition: 0.4s ease;
        }
        
        body:not(.light-mode) .detail-card { box-shadow: 0 30px 60px rgba(0,0,0,0.6); }
        body.light-mode .detail-card { box-shadow: 0 20px 50px rgba(0,0,0,0.05); }

        /* --- SISI KIRI: GAMBAR PRODUK --- */
        .product-gallery {
            flex: 1;
            position: relative;
            background: var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 500px;
            transition: 0.4s ease;
            border-right: 1px solid var(--border-color);
        }
        .product-gallery img {
            width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;
            transition: transform 0.5s ease;
        }
        .product-gallery:hover img { transform: scale(1.05); }
        
        .btn-share-img {
            position: absolute; top: 20px; left: 20px;
            width: 40px; height: 40px; border-radius: 50%;
            background: rgba(0,0,0,0.5); color: #fff;
            display: flex; align-items: center; justify-content: center;
            border: 1px solid rgba(255,255,255,0.2); cursor: pointer;
            backdrop-filter: blur(5px); transition: 0.3s; z-index: 2;
        }
        .btn-share-img:hover { background: var(--gold); border-color: var(--gold); color: #111; transform: scale(1.1); }

        /* --- SISI KANAN: INFO PRODUK --- */
        .product-info-wrap {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            transition: 0.4s ease;
        }

        .category-badge {
            display: inline-block; padding: 6px 15px; border-radius: 30px;
            border: 1px solid rgba(212, 175, 55, 0.3); color: var(--gold);
            font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;
            margin-bottom: 20px; align-self: flex-start;
            background: rgba(212, 175, 55, 0.05);
        }

        .product-title {
            font-family: 'Playfair Display', serif; font-size: 36px;
            color: var(--text-main); font-weight: 700; margin-bottom: 15px; line-height: 1.2;
            transition: 0.4s ease;
        }

        .product-price {
            font-family: 'Playfair Display', serif; font-size: 36px;
            color: var(--gold); font-weight: 700; margin-bottom: 25px; text-shadow: 0 0 15px rgba(212, 175, 55, 0.1);
        }

        .status-box {
            display: flex; align-items: center; gap: 15px; margin-bottom: 30px;
            padding-bottom: 25px; border-bottom: 1px solid var(--border-color);
            transition: 0.4s ease;
        }
        .status-label { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; transition: 0.4s ease; }
        .status-indicator { font-size: 11px; font-weight: 700; padding: 5px 12px; border-radius: 4px; letter-spacing: 1px; text-transform: uppercase; }
        .status-available { background: rgba(46, 204, 113, 0.1); color: var(--success); border: 1px solid rgba(46, 204, 113, 0.2); }
        .status-empty { background: rgba(231, 76, 60, 0.1); color: var(--danger); border: 1px solid rgba(231, 76, 60, 0.2); }

        .product-desc { font-size: 13px; color: var(--text-muted); line-height: 1.8; margin-bottom: 40px; flex-grow: 1; transition: 0.4s ease; }
        .product-desc-title { font-size: 12px; color: var(--text-main); text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 10px; display: block; transition: 0.4s ease; }

        /* --- TOMBOL AKSI --- */
        .action-group { display: flex; gap: 15px; margin-bottom: 25px; flex-wrap: wrap; }
        
        .btn-cart, .btn-wish {
            width: 58px; height: 58px; border-radius: 14px;
            background: transparent; border: 1px solid var(--border-color);
            color: var(--text-main); display: flex; align-items: center; justify-content: center;
            font-size: 20px; cursor: pointer; transition: 0.3s; flex-shrink: 0;
        }
        
        body:not(.light-mode) .btn-cart:hover { border-color: var(--gold); color: var(--gold); background: rgba(212, 175, 55, 0.05); }
        body.light-mode .btn-cart:hover { border-color: var(--gold); color: var(--gold); background: rgba(212, 175, 55, 0.1); }
        
        .btn-buy-now {
            flex: 1; min-width: 150px; height: 58px; background: var(--gold); color: #111;
            border: none; border-radius: 14px; font-size: 13px; font-weight: 800;
            text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .btn-buy-now:hover { background: var(--gold-hover); transform: translateY(-3px); box-shadow: 0 15px 30px rgba(212, 175, 55, 0.3); }

        .btn-wish { color: var(--text-muted); }
        body:not(.light-mode) .btn-wish:hover, body:not(.light-mode) .btn-wish.active { border-color: var(--danger); color: var(--danger); background: rgba(231, 76, 60, 0.05); }
        body.light-mode .btn-wish:hover, body.light-mode .btn-wish.active { border-color: var(--danger); color: var(--danger); background: rgba(231, 76, 60, 0.1); }

        .secure-note { display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 10px; color: var(--text-muted); letter-spacing: 0.5px; transition: 0.4s ease; }
        .secure-note i { color: var(--text-main); transition: 0.4s ease;}

        /* --- ULASAN SECTION --- */
        .reviews-section {
            background: var(--bg-surface); border: 1px solid var(--border-color);
            border-radius: 20px; padding: 40px; margin-bottom: 40px; transition: 0.4s ease;
        }
        .reviews-title {
            font-family: 'Playfair Display', serif; font-size: 24px; color: var(--gold);
            margin-bottom: 30px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); transition: 0.4s ease;
        }

        .review-item { padding: 20px 0; border-bottom: 1px dashed var(--border-color); transition: 0.4s ease; }
        .review-item:last-child { border-bottom: none; padding-bottom: 0; }
        
        .review-header { display: flex; justify-content: space-between; margin-bottom: 10px; }
        .review-user { font-size: 13px; font-weight: 700; color: var(--text-main); transition: 0.4s ease;}
        .review-stars { color: var(--gold); font-size: 12px; }
        .review-text { font-size: 13px; color: var(--text-muted); line-height: 1.6; font-style: italic; transition: 0.4s ease;}

        .admin-reply-item { margin-top: 15px; padding: 15px; background: rgba(212, 175, 55, 0.05); border-radius: 12px; border-left: 2px solid var(--gold); }
        .admin-reply-label { font-size: 10px; font-weight: 800; color: var(--gold); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px; }
        .admin-reply-text { font-size: 12px; color: var(--text-muted); line-height: 1.5; }

        .fs-badge-detail { background: #E84C3D; color: white; padding: 4px 10px; border-radius: 4px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; display: inline-block; }

        /* Tema SweetAlert Dinamis */
        .ecommerce-toast { border-radius: 12px !important; padding: 10px 20px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.8) !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }
        body.light-mode .ecommerce-toast { box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
        .premium-swal-popup { background: var(--bg-surface) !important; border: 1px solid var(--border-color) !important; border-radius: 12px !important; color: var(--text-main) !important; }
        .premium-swal-title { color: var(--gold) !important; font-family: 'Playfair Display', serif !important; }

        @media (max-width: 850px) {
            .detail-card { flex-direction: column; }
            .product-gallery { min-height: 350px; border-right: none; border-bottom: 1px solid var(--border-color); }
            .product-gallery div:first-child { height: 350px !important; }
            .product-info-wrap { padding: 35px 25px; }
            .product-title { font-size: 28px; }
            .action-group { gap: 12px; }
            .btn-buy-now { flex: 1 1 200px; height: 55px; font-size: 12px; }
            .btn-cart, .btn-wish { width: 55px; height: 55px; font-size: 18px; }
        }
        
        @media (max-width: 480px) {
            .btn-buy-now { flex: 100%; }
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

    <div class="container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <a href="javascript:history.back()" class="btn-back-top" style="margin-bottom: 0;"><i class="fas fa-arrow-left"></i> Kembali ke Katalog</a>
            @include('components.notif-bell')
        </div>

        <div class="detail-card">
            
            <div class="product-gallery" style="flex-direction: column;">
                <div style="position: relative; width: 100%; height: 400px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <button class="btn-share-img" onclick="shareProduct('{{ url()->current() }}', '{{ addslashes($product->nama_produk ?? 'Koleksi') }}')" title="Bagikan Produk ini">
                        <i class="fas fa-share-alt"></i>
                    </button>
                    @if(isset($product) && $product->gambar)
                        <img id="mainImage" src="{{ asset($product->gambar) }}" alt="{{ $product->nama_produk }}">
                    @else
                        <i class="fas fa-tshirt" style="font-size: 80px; color: var(--text-muted);"></i>
                    @endif
                </div>
                
                @if(isset($product) && $product->images && $product->images->count() > 0)
                <div style="display: flex; gap: 10px; padding: 20px; width: 100%; overflow-x: auto; background: var(--bg-surface); border-top: 1px solid var(--border-color);">
                    <img src="{{ asset($product->gambar) }}" onclick="document.getElementById('mainImage').src=this.src" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid var(--gold); transition: 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    @foreach($product->images as $img)
                        <img src="{{ asset($img->image_path) }}" onclick="document.getElementById('mainImage').src=this.src; document.querySelectorAll('.product-gallery img').forEach(i => i.style.borderColor='var(--border-color)'); this.style.borderColor='var(--gold)';" style="width: 70px; height: 70px; object-fit: cover; border-radius: 8px; cursor: pointer; border: 2px solid var(--border-color); transition: 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                    @endforeach
                </div>
                @endif
            </div>

            <div class="product-info-wrap">
                <div class="category-badge">{{ $product->kategori ?? 'Koleksi Eksklusif' }}</div>
                
                <h1 class="product-title">{{ $product->nama_produk ?? 'Produk Tidak Ditemukan' }}</h1>
                
                @if($flashSaleItem)
                    <div class="fs-badge-detail"><i class="fas fa-bolt"></i> Flash Sale: {{ $flashSale->nama_kampanye }}</div>
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
                        <div class="product-price" style="margin-bottom: 0; color: #E84C3D;">Rp {{ number_format($flashSaleItem->harga_diskon, 0, ',', '.') }}</div>
                        <div style="text-decoration: line-through; color: var(--text-muted); font-size: 18px; font-weight: 500;">Rp {{ number_format($product->harga ?? 0, 0, ',', '.') }}</div>
                    </div>
                @else
                    <div class="product-price">Rp {{ number_format($product->harga ?? 0, 0, ',', '.') }}</div>
                @endif

                <div class="status-box">
                    <span class="status-label">Status Produk:</span>
                    @if(isset($product) && $product->status == 'Tersedia')
                        <span class="status-indicator status-available">Tersedia (<span id="display-stok">Sisa {{ $product->stok ?? 1 }}</span>)</span>
                    @else
                        <span class="status-indicator status-empty">Terjual / Habis</span>
                    @endif
                </div>

                @if(isset($product) && $product->variants && $product->variants->count() > 0)
                <div class="variants-box" style="margin-bottom: 25px;">
                    <span class="product-desc-title">Pilih Varian</span>
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;" id="variant-options">
                        @foreach($product->variants as $var)
                            @if($var->stok > 0)
                            <label class="variant-option" style="border: 1px solid var(--border-color); padding: 10px 15px; border-radius: 8px; cursor: pointer; transition: 0.3s; font-size: 12px; display: inline-block;">
                                <input type="radio" name="variant_id" value="{{ $var->id }}" style="display: none;" onchange="selectVariant(this, '{{ $var->stok }}')">
                                <span style="font-weight: 600;">{{ $var->warna }} {{ $var->ukuran ? ' - ' . $var->ukuran : '' }}</span>
                                <br><span style="font-size: 10px; color: var(--text-muted);">Stok: {{ $var->stok }}</span>
                            </label>
                            @endif
                        @endforeach
                    </div>
                    <input type="hidden" id="selected_variant_id" value="">
                    <small id="variant-error" style="color: var(--danger); font-size: 11px; display: none; margin-top: 8px;"><i class="fas fa-exclamation-circle"></i> Silakan pilih varian terlebih dahulu!</small>
                </div>
                
                <style>
                    .variant-option:hover { border-color: var(--gold) !important; background: rgba(212, 175, 55, 0.05); }
                    .variant-option.selected { border-color: var(--gold) !important; background: rgba(212, 175, 55, 0.1); outline: 2px solid var(--gold); }
                </style>
                @endif

                <div class="product-desc">
                    <span class="product-desc-title">Deskripsi Produk</span>
                    {!! nl2br(e($product->deskripsi ?? 'Tidak ada deskripsi khusus untuk produk ini. Pakaian thrift berkualitas premium yang telah dikurasi secara ketat.')) !!}
                </div>

                <div class="action-group">
                    @if(isset($product) && $product->status == 'Tersedia')
                        <button class="btn-cart" onclick="addToCartWithVariant(event, {{ $product->id ?? 0 }})" title="Masukkan Keranjang">
                            <i class="fas fa-shopping-cart"></i>
                        </button>
                        
                        <a href="javascript:void(0)" onclick="buyNowWithVariant({{ $product->id ?? 0 }})" class="btn-buy-now">
                            <i class="fas fa-bolt"></i> Beli Sekarang
                        </a>
                    @else
                        <button class="btn-buy-now" style="background: var(--border-color); color: var(--text-muted); cursor: not-allowed;" disabled>
                            Produk Habis
                        </button>
                    @endif

                    @php
                        $isWishlisted = false;
                        if(Auth::check() && isset($product)) {
                            $isWishlisted = \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->exists();
                        }
                        
                        $wa_number = $setting->whatsapp ?? '6281234567890';
                        $wa_number = preg_replace('/[^0-9]/', '', $wa_number);
                        if (str_starts_with($wa_number, '0')) { $wa_number = '62' . substr($wa_number, 1); }
                        
                        $wa_message = urlencode("Halo Admin {$nama_toko}, saya tertarik dengan produk *" . ($product->nama_produk ?? 'ini') . "* (" . url()->current() . "). Apakah produk ini masih tersedia?");
                    @endphp

                    <div style="width: 100%; display: flex; gap: 15px; margin-top: 10px;">
                        <button class="btn-wish {{ $isWishlisted ? 'active' : '' }}" onclick="toggleWishlist(event, {{ $product->id ?? 0 }})" title="Tambah ke Wishlist">
                            <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart" id="wishlist-icon-{{ $product->id ?? 0 }}"></i>
                        </button>
                        <a href="https://wa.me/{{ $wa_number }}?text={{ $wa_message }}" target="_blank" class="btn-buy-now" style="background: #25D366; color: white; flex: 1;">
                            <i class="fab fa-whatsapp"></i> Tanya via WhatsApp
                        </a>
                    </div>
                </div>

                <div class="secure-note">
                    <i class="fas fa-shield-alt"></i> Pembayaran aman melalui ERNA Pay atau Transfer Bank.
                </div>
            </div>

        </div>

        @if(isset($product->reviews) && $product->reviews->count() > 0)
        <div class="reviews-section">
            <h2 class="reviews-title">Ulasan Pelanggan</h2>
            
            @foreach($product->reviews as $review)
            <div class="review-item">
                <div class="review-header">
                    <div class="review-user">
                        <i class="fas fa-user-circle" style="color: var(--text-muted); margin-right: 5px;"></i> 
                        {{ $review->user->name ?? 'Pelanggan' }}
                    </div>
                    <div class="review-stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                    </div>
                </div>
                <div class="review-text">
                    "{{ $review->komentar }}"
                </div>
                
                @if($review->foto)
                    <div style="margin-top: 15px;">
                        <img src="{{ asset($review->foto) }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; cursor: pointer; border: 1px solid var(--border-color);" onclick="window.open(this.src)" title="Klik untuk memperbesar">
                    </div>
                @endif
                
                @if($review->balasan_admin)
                    <div class="admin-reply-item">
                        <span class="admin-reply-label"><i class="fas fa-store"></i> Balasan Penjual:</span>
                        <div class="admin-reply-text">
                            {{ $review->balasan_admin }}
                        </div>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="reviews-section" style="margin-top: 40px; padding: 40px;">
            <h2 class="reviews-title" style="font-size: 20px; border: none; margin-bottom: 30px;">Mungkin Anda Juga Suka...</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px;">
                @foreach($relatedProducts as $rp)
                <a href="{{ url('/produk/detail/'.$rp->id) }}" class="related-card" style="text-decoration: none; color: inherit; background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; display: block; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); position: relative;">
                    <div style="position: relative; overflow: hidden; height: 220px;">
                        <img src="{{ asset($rp->gambar) }}" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s ease;">
                        <div class="related-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; opacity: 0; transition: 0.3s; backdrop-filter: blur(2px);">
                            <span style="color: white; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; border: 1px solid white; padding: 8px 15px; border-radius: 30px;">Lihat Detail</span>
                        </div>
                    </div>
                    <div style="padding: 20px;">
                        <span style="font-size: 9px; color: var(--gold); text-transform: uppercase; font-weight: 700; letter-spacing: 1px; margin-bottom: 8px; display: block;">{{ $rp->kategori }}</span>
                        <h4 style="font-size: 14px; margin-bottom: 10px; font-weight: 600; color: var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-family: 'Playfair Display', serif;">{{ $rp->nama_produk }}</h4>
                        <div style="color: var(--gold); font-weight: 700; font-size: 16px;">Rp {{ number_format($rp->harga, 0, ',', '.') }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <style>
            .related-card:hover { transform: translateY(-10px); border-color: var(--gold) !important; box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
            .related-card:hover img { transform: scale(1.1); }
            .related-card:hover .related-overlay { opacity: 1; }
            body.light-mode .related-card:hover { box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        </style>
        @endif

    </div>

    <script>
        function getSwalColors() {
            return {
                bg: document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a',
                text: document.body.classList.contains('light-mode') ? '#111111' : '#ffffff'
            };
        }

        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
            background: 'var(--bg-surface)', color: 'var(--text-main)', customClass: { popup: 'ecommerce-toast' }
        });

        function shareProduct(url, title) {
            let c = getSwalColors();
            if (navigator.share) {
                navigator.share({
                    title: 'Cek koleksi eksklusif ini: ' + title,
                    text: 'Saya menemukan barang Thrift keren di ERNA Thrifting!',
                    url: url
                }).catch(console.error);
            } else {
                navigator.clipboard.writeText(url).then(function() {
                    Toast.fire({ icon: 'success', title: 'Link produk berhasil disalin!', iconColor: '#D4AF37' });
                });
            }
        }

        function toggleWishlist(e, productId) {
            if (e) e.preventDefault();
            if(productId === 0) return;
            
            const btn = e.currentTarget;
            const icon = document.getElementById('wishlist-icon-' + productId);
            let c = getSwalColors();

            fetch(`/wishlist/tambah/${productId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'login_required') {
                    Swal.fire({
                        title: 'Akses Ditolak', text: 'Silakan login untuk menyimpan produk favorit Anda.', icon: 'info',
                        showCancelButton: true, confirmButtonText: 'Login Sekarang', confirmButtonColor: '#D4AF37', 
                        background: c.bg, color: c.text, customClass: { popup: 'premium-swal-popup' }
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = '/login';
                    });
                } else {
                    if(data.status === 'added') {
                        btn.classList.add('active');
                        if(icon) icon.classList.replace('far', 'fas');
                    } else {
                        btn.classList.remove('active');
                        if(icon) icon.classList.replace('fas', 'far');
                    }
                    Toast.fire({ icon: 'success', title: data.message, iconColor: '#D4AF37' });
                }
            });
        }

        function selectVariant(radio, stok) {
            document.querySelectorAll('.variant-option').forEach(el => el.classList.remove('selected'));
            radio.parentElement.classList.add('selected');
            document.getElementById('selected_variant_id').value = radio.value;
            document.getElementById('variant-error').style.display = 'none';
            document.getElementById('display-stok').innerText = 'Sisa ' + stok;
        }

        function addToCartWithVariant(e, productId) {
            if (e) e.preventDefault();
            if(productId === 0) return;
            
            const variantIdInput = document.getElementById('selected_variant_id');
            const hasVariants = variantIdInput !== null;
            let variantId = hasVariants ? variantIdInput.value : '';

            if (hasVariants && !variantId) {
                document.getElementById('variant-error').style.display = 'block';
                return;
            }

            let c = getSwalColors();

            fetch(`/keranjang/tambah/${productId}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ variant_id: variantId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'login_required') {
                    Swal.fire({
                        title: 'Akses Ditolak', text: 'Silakan login untuk menambahkan produk ke keranjang.', icon: 'info',
                        showCancelButton: true, confirmButtonText: 'Login Sekarang', confirmButtonColor: '#D4AF37', 
                        background: c.bg, color: c.text, customClass: { popup: 'premium-swal-popup' }
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = '/login';
                    });
                } else if (data.status === 'success') {
                    Toast.fire({ icon: 'success', title: data.message, iconColor: '#D4AF37' });
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function buyNowWithVariant(productId) {
            const variantIdInput = document.getElementById('selected_variant_id');
            const hasVariants = variantIdInput !== null;
            let variantId = hasVariants ? variantIdInput.value : '';

            if (hasVariants && !variantId) {
                document.getElementById('variant-error').style.display = 'block';
                return;
            }
            
            let url = '/beli-sekarang/' + productId;
            if (variantId) url += '?variant_id=' + variantId;
            window.location.href = url;
        }
    </script>
</body>
</html>