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
    <title>{{ $title ?? 'Katalog' }} | {{ $nama_toko }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        /* --- HEADER KATALOG --- */
        .catalog-header { padding: 60px 5% 40px; text-align: center; border-bottom: 1px solid var(--border-color); transition: 0.4s ease; }
        body:not(.light-mode) .catalog-header { background: radial-gradient(circle at top, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode .catalog-header { background: radial-gradient(circle at top, #ffffff 0%, var(--bg-dark) 100%); }
        .catalog-title { font-family: 'Playfair Display', serif; font-size: 36px; color: var(--gold); margin-bottom: 15px; font-weight: 700; text-transform: capitalize; }
        .catalog-desc { color: var(--text-muted); font-size: 13px; max-width: 600px; margin: 0 auto; line-height: 1.6; }

        /* --- FILTER KATEGORI --- */
        .filter-container { display: flex; justify-content: center; flex-wrap: wrap; gap: 15px; margin: 40px 5% 15px; }
        .filter-btn { padding: 10px 25px; background: transparent; border: 1px solid var(--border-color); color: var(--text-muted); border-radius: 30px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; transition: 0.3s; }
        .filter-btn:hover, .filter-btn.active { background: rgba(212, 175, 55, 0.1); border-color: var(--gold); color: var(--gold); }

        /* --- DROPDOWN SORT HARGA --- */
        .sort-container { display: flex; justify-content: flex-end; margin-bottom: 30px; padding: 0 5%; max-width: 1200px; margin-left: auto; margin-right: auto; }
        .sort-select { background-color: transparent; color: var(--gold); border: 1px solid var(--border-color); padding: 12px 20px; border-radius: 12px; font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; cursor: pointer; outline: none; transition: 0.3s; appearance: none; background-image: url('data:image/svg+xml;utf8,<svg fill="%23D4AF37" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/><path d="M0 0h24v24H0z" fill="none"/></svg>'); background-repeat: no-repeat; background-position: right 10px center; padding-right: 40px; }
        .sort-select:focus, .sort-select:hover { border-color: var(--gold); background-color: rgba(212, 175, 55, 0.05); }
        .sort-select option { background-color: var(--bg-surface); color: var(--text-main); }

        /* --- PRODUCT GRID PREMIUM --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 0 5% 80px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 30px; text-align: left; }
        .product-card { background: var(--bg-surface); border-radius: 12px; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 1px solid var(--border-color); display: flex; flex-direction: column; position: relative; }
        body:not(.light-mode) .product-card:hover { transform: translateY(-10px); border-color: rgba(212, 175, 55, 0.5); box-shadow: 0 20px 40px rgba(0,0,0,0.8), 0 0 20px rgba(212, 175, 55, 0.1); }
        body.light-mode .product-card:hover { transform: translateY(-10px); border-color: rgba(212, 175, 55, 0.5); box-shadow: 0 20px 40px rgba(0,0,0,0.05), 0 0 20px rgba(212, 175, 55, 0.05); }
        .product-img-wrap { position: relative; width: 100%; padding-top: 125%; background: var(--border-color); overflow: hidden; }
        .product-img-wrap img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain; background-color: #fff; object-position: center; filter: brightness(0.9); transition: transform 0.7s ease, filter 0.5s ease; }
        .product-card:hover .product-img-wrap img { filter: brightness(1.05); transform: scale(1.08); }
        .product-img-wrap i.main-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 60px; color: var(--text-muted); }
        .product-overlay { position: absolute; bottom: 0; left: 0; width: 100%; padding: 25px 10px 15px; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); display: flex; justify-content: center; align-items: center; gap: 10px; z-index: 2; opacity: 0; transform: translateY(20px); transition: all 0.3s ease; }
        .product-card:hover .product-overlay { opacity: 1; transform: translateY(0); }
        .action-btn { width: 35px; height: 35px; border-radius: 50%; background: rgba(26, 26, 26, 0.9); color: #f5f5f5; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: 0.3s; backdrop-filter: blur(5px); }
        .action-btn:hover { background: var(--gold); color: #111; border-color: var(--gold); transform: scale(1.1); }
        .share-btn:hover { background: #3498db; color: #fff; border-color: #3498db; }
        .wishlist-btn:hover, .wishlist-btn.active { background: var(--danger); color: #fff; border-color: var(--danger); }

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
            background: var(--danger);
            color: #fff;
            border-color: var(--danger);
        }
        .wishlist-btn-top.active {
            background: rgba(231, 76, 60, 0.15);
            color: var(--danger) !important;
            border-color: rgba(231, 76, 60, 0.3);
        }
        .wishlist-btn-top.active i {
            color: var(--danger) !important;
        }
        body.light-mode .wishlist-btn-top.active {
            background: rgba(231, 76, 60, 0.1);
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
        .buy-btn { width: auto; padding: 9px 20px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; background: var(--gold); color: #111; border: none; text-decoration: none; transition: 0.3s; display: inline-flex; align-items: center; }
        .buy-btn:hover { background: var(--gold-hover); transform: scale(1.05); }
        .product-info { padding: 20px; display: flex; flex-direction: column; flex-grow: 1; transition: 0.4s ease;}
        .product-category { font-size: 10px; color: var(--gold); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px; font-weight: 600; }
        .product-title { font-family: 'Playfair Display', serif; font-size: 18px; color: var(--text-main); text-decoration: none; margin-bottom: 8px; transition: 0.3s; display: block; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-title:hover { color: var(--gold); }
        .product-desc-preview { font-size: 12px; color: var(--text-muted); line-height: 1.5; margin-bottom: 15px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .product-price { font-size: 16px; color: var(--text-muted); margin-top: auto; font-weight: 600; }
        .empty-state { text-align: center; padding: 60px 20px; background: var(--bg-surface); border-radius: 12px; border: 1px dashed var(--border-color); grid-column: 1 / -1; }
        .empty-state i { font-size: 50px; color: var(--gold); opacity: 0.5; margin-bottom: 15px; }
        .empty-state h3 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 600; margin-bottom: 5px; color: var(--gold); }
        .empty-state p { font-size: 13px; color: var(--text-muted); }
        .ecommerce-toast { border-radius: 12px !important; padding: 10px 20px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.8) !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }
        body.light-mode .ecommerce-toast { box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
        /* --- THE ULTIMATE PAGINATION FIX --- */
        .container nav { width: 100%; margin-top: 40px; }
        .container nav .flex.justify-between.flex-1.sm\:hidden { display: none !important; }
        .container nav .hidden.sm\:flex-1 { display: flex !important; flex-direction: column !important; align-items: center !important; gap: 15px !important; }
        
        .container nav p.text-sm { color: var(--text-muted) !important; font-size: 12px !important; }
        
        .container nav .relative.z-0.inline-flex { 
            display: flex !important; 
            gap: 8px !important; 
            box-shadow: none !important; 
            border-radius: 0 !important; 
            border: none !important;
        }
        
        .container nav .relative.z-0.inline-flex > * {
            background: var(--bg-surface) !important;
            border: 1px solid var(--border-color) !important;
            color: var(--text-main) !important;
            border-radius: 10px !important;
            margin: 0 !important;
            padding: 0 !important;
            min-width: 40px !important;
            height: 40px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-decoration: none !important;
            transition: 0.3s !important;
        }
        
        .container nav .relative.z-0.inline-flex > a:hover {
            border-color: var(--gold) !important;
            color: var(--gold) !important;
            background: rgba(212, 175, 55, 0.05) !important;
        }
        
        .container nav .relative.z-0.inline-flex span[aria-current="page"] span {
            background: var(--gold) !important;
            color: #111 !important;
            border: none !important;
            width: 100% !important;
            height: 100% !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 9px !important;
        }
        
        .container nav svg { width: 18px !important; height: 18px !important; }
        
        @media (max-width: 768px) {
            .container nav .relative.z-0.inline-flex > * { min-width: 34px !important; height: 34px !important; font-size: 12px !important; }
            .container nav svg { width: 14px !important; height: 14px !important; }
        }

        /* --- MOBILE E-COMMERCE GRID (SHOPSTYLE) --- */
        @media (max-width: 576px) {
            .catalog-header { padding: 100px 20px 30px; }
            .catalog-title { font-size: 24px; }
            .catalog-desc { font-size: 11px; }
            
            .filter-container { gap: 8px; margin-top: 25px; }
            .filter-btn { padding: 8px 15px; font-size: 10px; }
            
            .sort-container { margin-bottom: 20px; }
            .sort-select { padding: 10px 15px; font-size: 11px; padding-right: 35px; }

            .container { padding-bottom: 40px; }
            .product-grid { 
                grid-template-columns: 1fr 1fr; 
                gap: 10px; 
            }
            
            .product-card { 
                border-radius: 8px; 
                border-color: var(--border-color);
            }
            
            .product-info { 
                padding: 10px; 
            }
            
            .product-category { font-size: 8px; margin-bottom: 4px; }
            .product-title { 
                font-size: 12px; 
                line-height: 1.4;
                height: 34px; /* Maintain 2 lines height */
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
                opacity: 1; /* Always visible on mobile but simplified */
                transform: translateY(0);
                padding: 10px 5px;
                background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
                gap: 5px;
            }

            .action-btn { width: 28px; height: 28px; font-size: 12px; }
            .buy-btn { padding: 6px 12px; font-size: 9px; }

            .ecommerce-toast {
                margin-bottom: 25px !important;
                border-radius: 50px !important;
                font-size: 12px !important;
                width: calc(100% - 40px) !important;
                border-color: var(--gold) !important;
                box-shadow: 0 8px 25px rgba(0,0,0,0.6) !important;
            }
        }
    </style>
</head>
<body>

    <script>
        if (localStorage.getItem('erna_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>
    <x-navbar :showBack="true" />

    <header class="catalog-header">
        <h1 class="catalog-title">{{ str_replace('Katalog', '', $title ?? 'Katalog Eksklusif') }}</h1>
        <p class="catalog-desc">Jelajahi rangkaian koleksi {{ $nama_kategori ?? 'fashion' }} berkualitas premium. Setiap produk telah melewati proses kurasi dan inspeksi ketat untuk menjamin kepuasan gaya Anda.</p>
    </header>

    <div class="filter-container">
        <a href="{{ url('/katalog/semua') }}" class="filter-btn {{ (!isset($nama_kategori) || $nama_kategori == 'Semua Produk' || $nama_kategori == 'Katalog Eksklusif') ? 'active' : '' }}">Semua</a>
        <a href="{{ url('/katalog/baju-pria') }}" class="filter-btn {{ (isset($nama_kategori) && $nama_kategori == 'Baju Pria') ? 'active' : '' }}">Baju Pria</a>
        <a href="{{ url('/katalog/celana-pria') }}" class="filter-btn {{ (isset($nama_kategori) && $nama_kategori == 'Celana Pria') ? 'active' : '' }}">Celana Pria</a>
        <a href="{{ url('/katalog/baju-wanita') }}" class="filter-btn {{ (isset($nama_kategori) && $nama_kategori == 'Baju Wanita') ? 'active' : '' }}">Baju Wanita</a>
        <a href="{{ url('/katalog/celana-wanita') }}" class="filter-btn {{ (isset($nama_kategori) && $nama_kategori == 'Celana Wanita') ? 'active' : '' }}">Celana Wanita</a>
    </div>

    <div class="sort-container">
        <select class="sort-select" onchange="applySort(this.value)">
            <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Urutkan: Paling Baru</option>
            <option value="termurah" {{ request('sort') == 'termurah' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
            <option value="termahal" {{ request('sort') == 'termahal' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
        </select>
    </div>

    <div class="container">
        @if(isset($products) && $products->count() > 0)
            <div class="product-grid">
                @foreach($products as $p)
                <div class="product-card">
                    <div class="product-img-wrap">
                        
                        <a href="{{ url('/produk/detail/'.$p->id) }}">
                            @if(isset($p->gambar) && $p->gambar != '')
                                <img src="{{ url('/' . $p->gambar) }}" alt="{{ $p->nama_produk }}">
                            @else
                                <i class="fas fa-tshirt main-icon"></i>
                            @endif
                        </a>

                        @php
                            $isWishlisted = false;
                            if(Auth::check()) {
                                $isWishlisted = \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $p->id)->exists();
                            }
                        @endphp
                        <button type="button" class="wishlist-btn-top {{ $isWishlisted ? 'active' : '' }}" onclick="toggleWishlist(event, {{ $p->id }})" title="Tambah ke Wishlist">
                            <i class="{{ $isWishlisted ? 'fas' : 'far' }} fa-heart" id="wishlist-icon-{{ $p->id }}" style="{{ $isWishlisted ? 'color: #e74c3c;' : '' }}"></i>
                        </button>

                        <div class="product-overlay">
                            <button type="button" class="action-btn share-btn" onclick="shareProduct('{{ url('/produk/detail/'.$p->id) }}', '{{ addslashes($p->nama_produk) }}')" title="Bagikan Produk">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            
                            <a href="{{ url('/beli-sekarang/'.$p->id) }}" class="buy-btn">BELI</a>

                            <button type="button" class="action-btn" onclick="addToCart(event, {{ $p->id }})" title="Masukkan Keranjang">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="product-info">
                        <div class="product-category">{{ $p->kategori ?? 'Umum' }}</div>
                        
                        <a href="{{ url('/produk/detail/'.$p->id) }}" class="product-title">
                            {{ $p->nama_produk ?? 'Nama Produk' }}
                        </a>
                        
                        <div class="product-desc-preview">
                            {{ \Illuminate\Support\Str::limit(strip_tags($p->deskripsi ?? 'Pakaian thrift eksklusif berkualitas premium.'), 60, '...') }}
                        </div>
                        
                        <div class="product-price">Rp {{ number_format($p->harga ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div style="margin-top: 40px; display: flex; justify-content: center;">
                {{ $products->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>Koleksi Tidak Ditemukan</h3>
                <p>Belum ada produk untuk kategori ini saat ini. Silakan kembali lagi nanti!</p>
            </div>
        @endif
    </div>

    <script>
        const Toast = Swal.mixin({
            toast: true, 
            position: window.innerWidth <= 576 ? 'bottom' : 'top-end', 
            showConfirmButton: false, 
            timer: 3000,
            background: 'var(--bg-surface)', 
            color: 'var(--text-main)', 
            customClass: { popup: 'ecommerce-toast' }
        });

        // Alert Dinamis Menyesuaikan Tema Terang/Gelap
        function getSwalColors() {
            return {
                bg: document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a',
                text: document.body.classList.contains('light-mode') ? '#111111' : '#ffffff'
            };
        }

        // FUNGSI SORT HARGA
        function applySort(sortValue) {
            // Mengambil URL saat ini
            const url = new URL(window.location.href);
            
            // Memasukkan parameter 'sort' ke dalam URL
            url.searchParams.set('sort', sortValue);
            
            // Memuat ulang halaman dengan URL yang baru
            window.location.href = url.href;
        }

        function shareProduct(url, title) {
            if (navigator.share) {
                navigator.share({
                    title: 'Cek koleksi eksklusif ini: ' + title,
                    text: 'Saya menemukan barang Thrift keren di ERNA Thrifting!',
                    url: url
                }).catch(console.error);
            } else {
                navigator.clipboard.writeText(url).then(function() {
                    Toast.fire({ icon: 'success', title: 'Link produk berhasil disalin!', iconColor: '#D4AF37' });
                }).catch(function(error) {
                    console.error("Gagal menyalin link: ", error);
                });
            }
        }

        function toggleWishlist(e, productId) {
            if (e) e.preventDefault();
            const btn = e.currentTarget;
            const icon = btn.querySelector('i');

            fetch(`/wishlist/tambah/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'login_required') {
                    let colors = getSwalColors();
                    Swal.fire({
                        title: 'Akses Ditolak', text: 'Silakan login untuk menyimpan produk favorit Anda.', icon: 'info',
                        showCancelButton: true, confirmButtonText: 'Login Sekarang', confirmButtonColor: '#D4AF37', 
                        background: colors.bg, color: colors.text, customClass: { popup: 'ecommerce-toast' }
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = '/login';
                    });
                } else {
                    if(data.status === 'added') {
                        icon.classList.replace('far', 'fas');
                        icon.style.color = '#e74c3c';
                    } else {
                        icon.classList.replace('fas', 'far');
                        icon.style.color = '';
                    }
                    Toast.fire({ icon: 'success', title: data.message, iconColor: '#D4AF37' });
                }
            });
        }

        function addToCart(e, productId) {
            if (e) e.preventDefault();

            fetch(`/keranjang/tambah/${productId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'login_required') {
                    let colors = getSwalColors();
                    Swal.fire({
                        title: 'Akses Ditolak', text: 'Silakan login untuk menambahkan produk ke keranjang.', icon: 'info',
                        showCancelButton: true, confirmButtonText: 'Login Sekarang', confirmButtonColor: '#D4AF37', 
                        background: colors.bg, color: colors.text, customClass: { popup: 'ecommerce-toast' }
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = '/login';
                    });
                } else if (data.status === 'success') {
                    let badge = document.getElementById('nav-cart-badge');
                    if(badge) {
                        badge.innerText = data.cart_count;
                    } else {
                        let cartWrapper = document.querySelector('.cart-wrapper');
                        if(cartWrapper) {
                            cartWrapper.innerHTML += `<span class="cart-badge" id="nav-cart-badge">${data.cart_count}</span>`;
                        }
                    }
                    Toast.fire({ icon: 'success', title: data.message, iconColor: '#D4AF37' });
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    <x-footer />
</body>
</html>
