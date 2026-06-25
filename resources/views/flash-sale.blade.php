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
    <title>{{ $title ?? 'Flash Sale' }} | {{ $nama_toko }}</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,500&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            --red-accent: #e74c3c;
            --red-hover: #c0392b;
            --red-dark: #2c0a0a;
        }

        body.light-mode {
            --bg-dark: #fcfcfc;
            --bg-surface: #ffffff;
            --text-main: #1a1a1a;
            --text-muted: #777777;
            --border-color: #eeeeee;
            --red-dark: #fff5f5;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body { 
            font-family: 'Montserrat', sans-serif; 
            background-color: var(--bg-dark); 
            color: var(--text-main); 
            overflow-x: hidden; 
            transition: background-color 0.4s ease, color 0.4s ease; 
        }

        /* --- HEADER FLASH SALE BURGUNDY --- */
        .fs-header { padding: 60px 5%; text-align: center; border-bottom: 1px solid var(--border-color); transition: 0.4s ease; }
        body:not(.light-mode) .fs-header { background: radial-gradient(circle at top, var(--red-dark) 0%, var(--bg-dark) 100%); }
        body.light-mode .fs-header { background: radial-gradient(circle at top, #ffeaea 0%, var(--bg-dark) 100%); }

        .fs-icon { font-size: 40px; color: var(--red-accent); margin-bottom: 20px; text-shadow: 0 0 20px rgba(231, 76, 60, 0.4); }
        .fs-title { font-family: 'Playfair Display', serif; font-size: 40px; color: var(--text-main); margin-bottom: 15px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; transition: 0.4s ease;}
        .fs-desc { color: var(--text-muted); font-size: 14px; margin-bottom: 30px; letter-spacing: 0.5px; transition: 0.4s ease;}
        
        .countdown-timer { display: flex; justify-content: center; gap: 15px; margin-top: 10px; }
        .time-box { border: 1px solid rgba(231, 76, 60, 0.3); padding: 15px 20px; border-radius: 8px; min-width: 80px; transition: 0.4s ease; }
        body:not(.light-mode) .time-box { background: rgba(0,0,0,0.6); }
        body.light-mode .time-box { background: rgba(255,255,255,0.6); }

        .time-number { display: block; font-family: 'Playfair Display', serif; font-size: 28px; color: var(--gold); font-weight: 700; }
        .time-label { font-size: 9px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }

        /* --- PRODUCT GRID PREMIUM --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 60px 5% 80px; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 30px; text-align: left; }
        .product-card { background: var(--bg-surface); border-radius: 12px; overflow: hidden; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 1px solid var(--border-color); display: flex; flex-direction: column; position: relative; }
        body:not(.light-mode) .product-card:hover { transform: translateY(-10px); border-color: rgba(231, 76, 60, 0.4); box-shadow: 0 20px 40px rgba(0,0,0,0.8), 0 0 20px rgba(231, 76, 60, 0.1); }
        body.light-mode .product-card:hover { transform: translateY(-10px); border-color: rgba(231, 76, 60, 0.4); box-shadow: 0 20px 40px rgba(0,0,0,0.05), 0 0 20px rgba(231, 76, 60, 0.05); }
        .flash-badge { position: absolute; top: 15px; right: 15px; background: var(--red-accent); color: white; padding: 6px 15px; font-size: 10px; font-weight: 700; border-radius: 20px; text-transform: uppercase; z-index: 5; box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3); letter-spacing: 1px; }
        .product-img-wrap { position: relative; width: 100%; padding-top: 125%; background: var(--border-color); overflow: hidden; transition: 0.4s ease; }
        .product-img-wrap img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; object-position: center; filter: brightness(0.9); transition: transform 0.7s ease, filter 0.5s ease; }
        .product-card:hover .product-img-wrap img { filter: brightness(1.05); transform: scale(1.08); }
        .product-img-wrap i.main-icon { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 60px; color: var(--text-muted); }
        .product-img-wrap::after { content: ''; position: absolute; bottom: 0; left: 0; width: 100%; height: 50%; background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); opacity: 0; transition: 0.4s ease; }
        .product-card:hover .product-img-wrap::after { opacity: 1; }
        .product-actions { position: absolute; bottom: 15px; left: 0; width: 100%; display: flex; justify-content: center; gap: 10px; z-index: 2; opacity: 0; transform: translateY(20px); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .product-card:hover .product-actions { opacity: 1; transform: translateY(0); }
        .action-btn { width: 36px; height: 36px; border-radius: 50%; background: rgba(26, 26, 26, 0.9); color: #f5f5f5; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.1); cursor: pointer; transition: 0.3s; backdrop-filter: blur(5px); }
        .action-btn:hover { background: var(--red-accent); color: #fff; border-color: var(--red-accent); transform: scale(1.1); }

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
            background: rgba(231, 76, 60, 0.15);
            color: var(--red-accent) !important;
            border-color: rgba(231, 76, 60, 0.3);
        }
        .wishlist-btn-top.active i {
            color: var(--red-accent) !important;
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
        .btn-buy { width: auto; padding: 0 20px; border-radius: 20px; font-size: 11px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; background: var(--red-accent); color: #fff; border: 1px solid var(--red-accent); text-decoration: none; display: flex; align-items: center; transition: 0.3s;}
        .btn-buy:hover { background: var(--red-hover); transform: scale(1.05); }
        .product-info { padding: 20px; display: flex; flex-direction: column; gap: 8px; flex-grow: 1; transition: 0.4s ease;}
        .product-cat { font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; font-weight: 600; }
        .product-name { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 600; color: var(--text-main); line-height: 1.3; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; transition: 0.4s ease;}
        .product-price { font-size: 16px; color: var(--gold); font-weight: 700; letter-spacing: 1px; margin-top: auto; }
        .empty-state { text-align: center; padding: 60px 20px; background: var(--bg-surface); border-radius: 12px; border: 1px dashed var(--border-color); transition: 0.4s ease;}
        .empty-state i { font-size: 50px; color: var(--border-color); margin-bottom: 15px; }
        .empty-state h3 { font-size: 18px; font-weight: 600; margin-bottom: 5px; color: var(--text-main); }
        .empty-state p { font-size: 13px; color: var(--text-muted); }
        .ecommerce-toast { border-radius: 12px !important; padding: 10px 20px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.8) !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }
        body.light-mode .ecommerce-toast { box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
        .premium-swal-popup { background: var(--bg-surface) !important; border: 1px solid var(--border-color) !important; border-radius: 12px !important; color: var(--text-main) !important; }
        .premium-swal-popup { background: var(--bg-surface) !important; border: 1px solid var(--border-color) !important; border-radius: 12px !important; color: var(--text-main) !important; }

        /* --- MOBILE OPTIMIZATION --- */
        @media (max-width: 576px) {
            .fs-header { padding: 100px 20px 40px; }
            .fs-icon { font-size: 30px; margin-bottom: 10px; }
            .fs-title { font-size: 26px; letter-spacing: 1px; }
            .fs-desc { font-size: 11px; margin-bottom: 20px; }
            
            .countdown-timer { gap: 8px; }
            .time-box { padding: 8px 10px; min-width: 65px; border-radius: 6px; }
            .time-number { font-size: 18px; }
            .time-label { font-size: 8px; }

            .container { padding: 30px 10px 60px; }
            .product-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            
            .product-card { border-radius: 8px; }
            .flash-badge { padding: 4px 10px; font-size: 8px; top: 10px; right: 10px; border-radius: 4px; }
            
            .product-info { padding: 10px; gap: 4px; }
            .product-cat { font-size: 8px; letter-spacing: 1px; }
            .product-name { font-size: 12px; height: 32px; line-height: 1.3; margin-bottom: 4px; }
            .product-price { font-size: 14px; }
            
            .product-actions { 
                opacity: 1; transform: translateY(0); 
                padding: 10px 5px; 
                background: linear-gradient(to top, rgba(0,0,0,0.6), transparent); 
                gap: 5px;
            }
            .action-btn { width: 28px; height: 28px; font-size: 11px; }
            .btn-buy { padding: 5px 12px; font-size: 9px; height: 28px; }

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

    <header class="fs-header">
        <i class="fas fa-bolt fs-icon"></i>
        <h1 class="fs-title">{{ $title ?? 'Midnight Flash Sale' }}</h1>
        <p class="fs-desc">Waktu Terbatas! Amankan koleksi eksklusif dengan penawaran spesial malam ini.</p>
        
        <div class="countdown-timer" id="fs-timer" style="display: none;">
            <div class="time-box"><span class="time-number" id="fs-days">00</span><span class="time-label">Hari</span></div>
            <div class="time-box"><span class="time-number" id="fs-hours">00</span><span class="time-label">Jam</span></div>
            <div class="time-box"><span class="time-number" id="fs-mins">00</span><span class="time-label">Menit</span></div>
            <div class="time-box"><span class="time-number" id="fs-secs">00</span><span class="time-label">Detik</span></div>
        </div>
    </header>

    <div class="container">
        @if(isset($products) && $products->count() > 0)
            <div class="product-grid">
                @foreach($products as $item)
                @php $p = $item->product; @endphp
                <div class="product-card">
                    <div class="flash-badge">HOT ITEM</div>
                    
                    <div class="product-img-wrap">
                        @if(isset($p->gambar) && $p->gambar != '')
                            <img src="{{ url('/' . $p->gambar) }}" alt="{{ $p->nama_produk }}">
                        @else
                            <i class="fas fa-tshirt main-icon"></i>
                        @endif

                        <button type="button" class="wishlist-btn-top {{ \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $p->id)->exists() ? 'active' : '' }}" onclick="toggleWishlist(event, {{ $p->id }})" title="Tambah ke Wishlist">
                            <i class="{{ \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $p->id)->exists() ? 'fas' : 'far' }} fa-heart" 
                               style="{{ \App\Models\Wishlist::where('user_id', Auth::id())->where('product_id', $p->id)->exists() ? 'color: var(--red-accent);' : '' }}"></i>
                        </button>

                        <div class="product-actions">
                            <button class="action-btn" onclick="shareProduct('{{ url('/produk/detail/'.$p->id) }}')" title="Bagikan Produk" type="button">
                                <i class="fas fa-share-alt"></i>
                            </button>
                            
                            <a href="{{ url('/beli-sekarang/'.$p->id) }}" class="btn-buy">Beli</a>

                            <button type="button" class="action-btn" onclick="addToCart(event, {{ $p->id }})" title="Masukkan Keranjang">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>
                    </div>
                    
                    <a href="{{ url('/produk/detail/'.$p->id) }}" style="text-decoration: none;">
                        <div class="product-info">
                            <div class="product-cat">{{ $p->kategori ?? 'Flash Sale' }}</div>
                            <div class="product-name">{{ $p->nama_produk ?? 'Nama Produk' }}</div>
                            <div style="display: flex; flex-direction: column;">
                                <span style="font-size: 11px; color: var(--text-muted); text-decoration: line-through;">Rp {{ number_format($p->harga ?? 0, 0, ',', '.') }}</span>
                                <div class="product-price">Rp {{ number_format($item->harga_diskon ?? 0, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>Flash Sale Berakhir</h3>
                <p>Waktu Flash Sale telah habis atau stok produk sudah ludes terjual. Nantikan event eksklusif kami berikutnya!</p>
            </div>
        @endif
    </div>

    <script>
        // Alert Dinamis Menyesuaikan Tema Terang/Gelap
        function getSwalColors() {
            return {
                bg: document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a',
                text: document.body.classList.contains('light-mode') ? '#111111' : '#ffffff'
            };
        }

        // Fitur Countdown Timer Flash Sale
        @if(isset($flashSaleEnd))
            let fsEndTime = new Date("{{ $flashSaleEnd }}").getTime();
            let timerElement = document.getElementById('fs-timer');
            
            let x = setInterval(function() {
                let now = new Date().getTime();
                let distance = fsEndTime - now;

                if (distance > 0) {
                    timerElement.style.display = "flex";
                    document.getElementById("fs-days").innerHTML = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                    document.getElementById("fs-hours").innerHTML = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                    document.getElementById("fs-mins").innerHTML = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                    document.getElementById("fs-secs").innerHTML = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
                } else {
                    clearInterval(x);
                    timerElement.innerHTML = "<div style='color:var(--red-accent); font-weight:bold; letter-spacing:2px; font-size:18px;'>FLASH SALE BERAKHIR</div>";
                    timerElement.style.display = "flex";
                }
            }, 1000);
        @endif

        // Fitur Salin Link (Share)
        function shareProduct(link) {
            let c = getSwalColors();
            navigator.clipboard.writeText(link).then(function() {
                Swal.fire({
                    toast: true, 
                    position: window.innerWidth <= 576 ? 'bottom' : 'top-end', 
                    showConfirmButton: false, 
                    timer: 2500,
                    icon: 'success', title: 'Link berhasil disalin!',
                    background: c.bg, color: c.text, customClass: { popup: 'ecommerce-toast' }
                });
            }).catch(function(error) {
                console.error("Gagal menyalin link: ", error);
            });
        }

        // Fitur Wishlist 
        function toggleWishlist(e, productId) {
            if (e) e.preventDefault();
            const btn = e.currentTarget;
            const icon = btn.querySelector('i');
            let c = getSwalColors();

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
                    Swal.fire({
                        title: 'Akses Ditolak', text: 'Silakan login untuk menyimpan produk favorit Anda.', icon: 'info',
                        showCancelButton: true, confirmButtonText: 'Login Sekarang', confirmButtonColor: '#e74c3c', 
                        background: c.bg, color: c.text, customClass: { popup: 'premium-swal-popup' }
                    }).then((result) => {
                        if (result.isConfirmed) window.location.href = '/login';
                    });
                } else {
                    if(data.status === 'added') {
                        icon.classList.replace('far', 'fas');
                        icon.style.color = 'var(--red-accent)';
                    } else {
                        icon.classList.replace('fas', 'far');
                        icon.style.color = '';
                    }
                    Swal.fire({ 
                        toast: true, 
                        position: window.innerWidth <= 576 ? 'bottom' : 'top-end', 
                        showConfirmButton: false, 
                        timer: 2500, 
                        icon: 'success', 
                        title: data.message, 
                        background: c.bg, 
                        color: c.text, 
                        customClass: { popup: 'ecommerce-toast' } 
                    });
                }
            });
        }

        // Fitur Tambah Keranjang
        function addToCart(e, productId) {
            if (e) e.preventDefault();
            let c = getSwalColors();

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
                    Swal.fire({
                        title: 'Akses Ditolak', text: 'Silakan login untuk menambahkan produk ke keranjang.', icon: 'info',
                        showCancelButton: true, confirmButtonText: 'Login Sekarang', confirmButtonColor: '#e74c3c', 
                        background: c.bg, color: c.text, customClass: { popup: 'premium-swal-popup' }
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
                    Swal.fire({ 
                        toast: true, 
                        position: window.innerWidth <= 576 ? 'bottom' : 'top-end', 
                        showConfirmButton: false, 
                        timer: 2500, 
                        icon: 'success', 
                        title: data.message, 
                        background: c.bg, 
                        color: c.text, 
                        customClass: { popup: 'ecommerce-toast' } 
                    });
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    <x-footer />
</body>
</html>