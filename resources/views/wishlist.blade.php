@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist Saya | {{ $nama_toko }}</title>
    
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
            --red-accent: #E84C3D;
        }

        /* --- TEMA TERANG (LIGHT MODE) GLOBAL --- */
        body.light-mode {
            --bg-dark: #f5f5f5;
            --bg-surface: #ffffff;
            --text-main: #111111;
            --text-muted: #666666;
            --border-color: #dddddd;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 100px 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            color: var(--gold);
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Wishlist Grid */
        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 30px;
        }

        .wishlist-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 15px;
            overflow: hidden;
            transition: 0.4s ease;
            position: relative;
        }

        .wishlist-card:hover {
            transform: translateY(-10px);
            border-color: var(--gold);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }

        .img-wrap {
            height: 350px;
            overflow: hidden;
            position: relative;
        }

        .img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: 0.5s ease;
        }

        .wishlist-card:hover .img-wrap img {
            transform: scale(1.1);
        }

        .remove-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 35px;
            height: 35px;
            background: rgba(0,0,0,0.5);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50%;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            backdrop-filter: blur(5px);
            transition: 0.3s;
            z-index: 10;
        }

        .remove-btn:hover {
            background: var(--red-accent);
            border-color: var(--red-accent);
            transform: scale(1.1);
        }

        .card-info {
            padding: 25px;
        }

        .product-cat {
            font-size: 10px;
            color: var(--gold);
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .product-name {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 600;
            color: var(--text-main);
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            transition: 0.3s;
        }

        .product-name:hover {
            color: var(--gold);
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 20px;
        }

        .btn-action {
            display: flex;
            gap: 10px;
        }

        .btn-buy {
            flex: 1;
            background: var(--gold);
            color: #111;
            text-decoration: none;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .btn-buy:hover {
            background: var(--gold-hover);
            transform: translateY(-3px);
        }

        .empty-wishlist {
            text-align: center;
            padding: 100px 0;
        }

        .empty-icon {
            font-size: 80px;
            color: var(--border-color);
            margin-bottom: 30px;
        }

        .btn-back {
            display: inline-block;
            margin-top: 30px;
            color: var(--gold);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid var(--gold);
            padding-bottom: 5px;
        }

        .nav-back {
            position: absolute;
            top: 40px;
            left: 40px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }

        .nav-back:hover {
            color: var(--gold);
            transform: translateX(-5px);
        }

        /* Custom Swal */
        .ecommerce-toast { border-radius: 12px !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }

        /* --- MOBILE OPTIMIZATION --- */
        @media (max-width: 576px) {
            .container { padding: 80px 10px 40px; }
            .page-title { font-size: 32px; }
            .page-subtitle { font-size: 10px; }
            
            .wishlist-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
            .wishlist-card { border-radius: 8px; }
            .img-wrap { height: 220px; }
            
            .card-info { padding: 12px; }
            .product-cat { font-size: 8px; margin-bottom: 4px; }
            .product-name { font-size: 13px; height: 36px; margin-bottom: 8px; }
            .product-price { font-size: 14px; margin-bottom: 12px; }
            
            .btn-action { flex-direction: column; gap: 6px; }
            .btn-buy { width: 100%; padding: 8px 5px; font-size: 9px; text-align: center; }
            .remove-btn { width: 28px; height: 28px; font-size: 12px; top: 10px; right: 10px; }

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

    <!-- SCRIPT PENGINGAT TEMA -->
    <script>
        if (localStorage.getItem('erna_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>

    <div style="display: flex; justify-content: space-between; align-items: center; padding: 40px;">
        <a href="{{ url('/') }}" class="nav-back" style="position: static;">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>
        @include('components.notif-bell')
    </div>

    <div class="container">
        <header class="page-header">
            <h1 class="page-title">Wishlist Saya</h1>
            <p class="page-subtitle">Koleksi Terkurasi Yang Anda Sukai</p>
        </header>

        @if($wishlists->count() > 0)
        <div class="wishlist-grid">
            @foreach($wishlists as $w)
            @php $p = $w->product; @endphp
            <div class="wishlist-card" id="wishlist-{{ $w->id }}">
                <button class="remove-btn" onclick="removeFromWishlist({{ $p->id }}, {{ $w->id }})" title="Hapus dari Wishlist">
                    <i class="fas fa-times"></i>
                </button>
                <div class="img-wrap">
                    <a href="{{ url('/produk/detail/'.$p->id) }}">
                        <img src="{{ asset($p->gambar) }}" alt="{{ $p->nama_produk }}">
                    </a>
                </div>
                <div class="card-info">
                    <div class="product-cat">{{ $p->kategori }}</div>
                    <a href="{{ url('/produk/detail/'.$p->id) }}" class="product-name">{{ $p->nama_produk }}</a>
                    <div class="product-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</div>
                    <div class="btn-action">
                        <a href="{{ url('/produk/detail/'.$p->id) }}" class="btn-buy">Lihat Detail</a>
                        <a href="{{ url('/beli-sekarang/'.$p->id) }}" class="btn-buy" style="background: transparent; color: var(--gold); border: 1px solid var(--gold);">Beli Langsung</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-wishlist">
            <div class="empty-icon"><i class="far fa-heart"></i></div>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 32px;">Wishlist Anda Kosong</h2>
            <p style="color: var(--text-muted); margin-top: 15px;">Belum ada koleksi yang Anda simpan. Mulailah menjelajahi katalog kami.</p>
            <a href="{{ url('/katalog/semua') }}" class="btn-back">Lihat Katalog</a>
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

        function getSwalColors() {
            return {
                bg: document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a',
                text: document.body.classList.contains('light-mode') ? '#111111' : '#ffffff'
            };
        }

        function removeFromWishlist(productId, wishlistId) {
            const colors = getSwalColors();
            Swal.fire({
                title: 'Hapus dari Wishlist?',
                text: "Koleksi ini akan dihapus dari daftar favorit Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#E84C3D',
                cancelButtonColor: document.body.classList.contains('light-mode') ? '#888888' : '#2a2a2a',
                confirmButtonText: 'Ya, Hapus',
                background: colors.bg,
                color: colors.text
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/wishlist/tambah/${productId}`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'removed') {
                            document.getElementById(`wishlist-${wishlistId}`).style.display = 'none';
                            Swal.fire({
                                toast: true, position: 'top-end', showConfirmButton: false, timer: 2000, icon: 'success', title: 'Dihapus dari Wishlist', background: colors.bg, color: colors.text
                            });
                            // If empty after removal
                            setTimeout(() => {
                                if(document.querySelectorAll('.wishlist-card[style="display: none;"]').length === {{ $wishlists->count() }}) {
                                    location.reload();
                                }
                            }, 1000);
                        }
                    });
                }
            });
        }
    </script>
    <x-footer />
</body>
</html>