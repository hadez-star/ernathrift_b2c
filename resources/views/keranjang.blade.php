@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
    $cartCount = Auth::check() ? \App\Models\Cart::where('user_id', Auth::id())->sum('jumlah') : 0;
    $totalHarga = 0;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Keranjang Belanja' }} | {{ $nama_toko }}</title>
    
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
            --success: #2ecc71;
            --input-bg: rgba(255, 255, 255, 0.05);
        }

        /* --- TEMA TERANG (LIGHT MODE) GLOBAL --- */
        body.light-mode {
            --bg-dark: #f5f5f5;
            --bg-surface: #ffffff;
            --text-main: #111111;
            --text-muted: #666666;
            --border-color: #dddddd;
            --input-bg: rgba(0, 0, 0, 0.05);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            padding: 40px 20px;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        body:not(.light-mode) { background: radial-gradient(circle at top center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at top center, #ffffff 0%, var(--bg-dark) 100%); }

        .container { max-width: 1000px; margin: 0 auto; animation: eleganceIn 0.8s ease forwards; }

        @keyframes eleganceIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* --- HEADER --- */
        .header { display: flex; align-items: center; margin-bottom: 40px; position: relative; }
        .btn-back { color: var(--text-muted); text-decoration: none; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px; z-index: 5; }
        .btn-back i { font-size: 12px; }
        .btn-back:hover { color: var(--gold); transform: translateX(-3px); }
        .header h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--gold); margin: 0; position: absolute; left: 50%; transform: translateX(-50%); width: 100%; text-align: center; pointer-events: none; }

        /* --- LAYOUT KONTEN --- */
        .cart-layout { display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px; align-items: start; }

        /* --- SISI KIRI: DAFTAR BARANG --- */
        .cart-items-container { background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 30px; transition: 0.4s ease; }
        body:not(.light-mode) .cart-items-container { box-shadow: 0 20px 40px rgba(0,0,0,0.4); border-color: rgba(212, 175, 55, 0.15); }
        body.light-mode .cart-items-container { box-shadow: 0 20px 40px rgba(0,0,0,0.05); }

        .cart-header-title { font-size: 12px; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); font-weight: 700; }

        .cart-item { display: flex; gap: 20px; padding: 25px 0; border-bottom: 1px solid var(--border-color); position: relative; align-items: flex-start; transition: 0.3s; }
        .cart-item:last-child { border-bottom: none; padding-bottom: 0; }
        .cart-item:hover { background: rgba(255, 255, 255, 0.02); }

        .item-img { width: 120px; height: 120px; border-radius: 14px; overflow: hidden; border: 1px solid var(--border-color); background: var(--bg-dark); flex-shrink: 0; }
        .item-img img { width: 100%; height: 100%; object-fit: cover; }

        .item-info { flex: 1; min-width: 0; }
        .item-category { font-size: 10px; color: var(--gold); text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 8px; font-weight: 700; }
        .item-name { font-size: 15px; font-weight: 600; color: var(--text-main); margin-bottom: 12px; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .item-price { font-family: 'Montserrat', sans-serif; font-size: 16px; color: var(--text-main); font-weight: 700; margin-bottom: 15px; }

        .item-qty-wrap { display: flex; align-items: center; gap: 15px; }
        .qty-badge { background: var(--input-bg); padding: 5px 12px; border-radius: 6px; font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

        .btn-delete { color: var(--danger); background: transparent; border: none; padding: 5px; font-size: 14px; cursor: pointer; transition: 0.3s; position: absolute; top: 25px; right: 0; }
        .btn-delete span { display: none; }
        .btn-delete:hover { color: #ff5e4d; transform: scale(1.1); }

        /* --- SISI KANAN: RINGKASAN BELANJA --- */
        .summary-card { background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 30px; position: sticky; top: 40px; transition: 0.4s ease; }
        body:not(.light-mode) .summary-card { box-shadow: 0 20px 50px rgba(0,0,0,0.5), inset 0 0 20px rgba(212, 175, 55, 0.03); border-color: rgba(212, 175, 55, 0.3); }
        body.light-mode .summary-card { box-shadow: 0 20px 50px rgba(0,0,0,0.05); }

        .summary-title { font-family: 'Playfair Display', serif; font-size: 22px; color: var(--gold); margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid var(--border-color); }

        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; font-size: 13px; color: var(--text-muted); }
        .summary-row.discount { color: var(--success); font-weight: 600; }

        .summary-total { display: flex; justify-content: space-between; align-items: center; margin-top: 25px; padding-top: 20px; border-top: 1px dashed var(--border-color); }
        .summary-total .label { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--text-main); }
        .summary-total .amount { font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700; color: var(--gold); }

        .btn-checkout { display: block; width: 100%; padding: 16px; background: var(--gold); color: #111; text-align: center; border-radius: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 12px; text-decoration: none; margin-top: 30px; transition: 0.3s; border: 1px solid var(--gold); }
        .btn-checkout:hover { background: var(--gold-hover); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3); }

        /* --- EMPTY STATE KOSONG --- */
        .empty-state { background: var(--bg-surface); border: 1px dashed var(--border-color); border-radius: 16px; padding: 80px 20px; text-align: center; grid-column: 1 / -1; transition: 0.4s ease; }
        body:not(.light-mode) .empty-state { border-color: rgba(212, 175, 55, 0.3); box-shadow: inset 0 0 30px rgba(0,0,0,0.3); }
        
        .empty-state i { font-size: 70px; color: var(--gold); opacity: 0.3; margin-bottom: 25px; }
        .empty-state h2 { font-family: 'Playfair Display', serif; font-size: 26px; color: var(--gold); margin-bottom: 10px; }
        .empty-state p { font-size: 13px; color: var(--text-muted); margin-bottom: 35px; }
        
        .btn-shop { display: inline-block; padding: 15px 35px; background: transparent; color: var(--gold); border: 1px solid var(--gold); border-radius: 30px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; text-decoration: none; transition: 0.3s; }
        .btn-shop:hover { background: var(--gold); color: var(--bg-dark); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }

        /* --- THEME CUSTOM SWEETALERT LENGKAP --- */
        .ecommerce-toast { border-radius: 12px !important; padding: 10px 20px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.8) !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }
        body.light-mode .ecommerce-toast { box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
        .premium-swal-popup { background: var(--bg-surface) !important; border: 1px solid var(--border-color) !important; border-radius: 16px !important; padding: 2.5em 2em !important; box-shadow: 0 20px 50px rgba(0,0,0,0.7) !important; }
        body.light-mode .premium-swal-popup { box-shadow: 0 20px 50px rgba(0,0,0,0.1) !important; }

        @media (max-width: 850px) {
            .cart-layout { grid-template-columns: 1fr; gap: 20px; }
            .header { margin-bottom: 30px; height: 40px; }
            .btn-back { position: relative; left: auto; transform: none !important; margin-bottom: 0; }
            .hide-mobile { display: none; }
            .header h1 { font-size: 20px; width: auto; white-space: nowrap; }
            .cart-items-container { padding: 20px; }
            .cart-item { gap: 15px; padding: 20px 0; }
            .item-img { width: 90px; height: 90px; border-radius: 10px; }
            .item-name { font-size: 13px; margin-bottom: 8px; }
            .item-price { font-size: 14px; margin-bottom: 10px; }
            .btn-delete { top: 15px; font-size: 16px; }
            .summary-card { padding: 25px; position: relative; top: 0; }
        }
        
        @media (max-width: 480px) {
            .btn-back span { display: none; }
            .header h1 { font-size: 18px; }
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

    <div class="container">
        
        <div class="header">
            <a href="{{ url('/') }}" class="btn-back"><i class="fas fa-arrow-left"></i> <span>Kembali <span class="hide-mobile">Belanja</span></span></a>
            <h1>Keranjang Belanja</h1>
            <div style="position: absolute; right: 0; display: flex; align-items: center; gap: 15px;">
                @include('components.notif-bell')
            </div>
        </div>

        @php
            $totalHarga = 0; 
            $totalBarang = 0;
            if(isset($carts) && $carts->count() > 0) {
                foreach($carts as $cart) {
                    $totalHarga += ($cart->product->harga * $cart->jumlah); 
                    $totalBarang += $cart->jumlah;
                }
            }

            $diskon = 0;
            if ($totalBarang > 0 && Auth::check() && Auth::user()->vip_paket == 'GOLD') {
                $diskon = $totalHarga * 0.05;
            }
            $totalBayar = $totalHarga - $diskon;
        @endphp

        @if(isset($carts) && $totalBarang > 0)
            <div class="cart-layout">
                
                <div class="cart-items-container">
                    <div class="cart-header-title">Daftar Produk ({{ $totalBarang }} Item)</div>
                    
                    @foreach($carts as $cart)
                        <div class="cart-item">
                            <div class="item-img">
                                @if($cart->product && $cart->product->gambar)
                                    <img src="{{ asset($cart->product->gambar) }}" alt="{{ $cart->product->nama_produk }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 10px;">
                                @else
                                    <i class="fas fa-tshirt"></i>
                                @endif
                            </div>
                            
                            <div class="item-info">
                                <div class="item-category">{{ $cart->product->kategori ?? 'Pakaian' }}</div>
                                <div class="item-name">
                                    {{ $cart->product->nama_produk ?? 'Produk Dihapus Admin' }}
                                    @if($cart->variant)
                                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 5px; font-weight: normal;">
                                            Varian: {{ $cart->variant->warna }} {{ $cart->variant->ukuran ? ' - ' . $cart->variant->ukuran : '' }}
                                        </div>
                                    @endif
                                </div>
                                <div class="item-price">Rp {{ number_format($cart->product->harga ?? 0, 0, ',', '.') }}</div>
                                <div class="item-qty-wrap">
                                    <span class="qty-badge">Kuantitas: <b>{{ $cart->jumlah }}x</b></span>
                                </div>
                            </div>

                            <button class="btn-delete" onclick="konfirmasiHapus('{{ url('/keranjang/hapus/' . $cart->id) }}')">
                                <i class="fas fa-trash-alt"></i> Hapus
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="summary-card">
                    <h2 class="summary-title">Ringkasan Belanja</h2>
                    
                    <div class="summary-row">
                        <span>Total Harga ({{ $totalBarang }} barang)</span>
                        <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>

                    @if(Auth::check() && Auth::user()->vip_paket == 'GOLD')
                        <div class="summary-row discount">
                            <span>Diskon VIP (5%)</span>
                            <span>-Rp {{ number_format($diskon, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="summary-total">
                        <span class="label">Total Bayar</span>
                        <span class="amount">Rp {{ number_format($totalBayar, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ url('/checkout') }}" class="btn-checkout">Lanjutkan Checkout</a>
                </div>

            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <h2>Keranjang Masih Kosong</h2>
                <p>Koleksi thrift eksklusif kami menunggu untuk Anda pinang. Yuk, temukan gaya Anda!</p>
                <a href="{{ url('/katalog/semua') }}" class="btn-shop">Mulai Belanja</a>
            </div>
        @endif

    </div>

    <script>
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
            background: 'var(--bg-surface)', color: 'var(--text-main)',
            customClass: { popup: 'ecommerce-toast' }
        });
        
        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}", iconColor: '#D4AF37' });
        @endif

        function konfirmasiHapus(urlHapus) {
            Swal.fire({
                title: '<span style="font-family: \'Playfair Display\', serif; font-size: 28px; color: #e74c3c; display: block; margin-bottom: 5px;">Hapus Produk?</span>',
                html: '<span style="font-family: \'Montserrat\', sans-serif; font-size: 14px; color: var(--text-main);">Apakah Anda yakin ingin mengeluarkan produk<br>ini dari keranjang?</span>',
                icon: 'warning',
                iconColor: '#e74c3c',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#888888',
                confirmButtonText: '<span style="font-family: \'Montserrat\', sans-serif; font-weight: 500;">Ya, Hapus</span>',
                cancelButtonText: '<span style="font-family: \'Montserrat\', sans-serif; font-weight: 500;">Batal</span>',
                background: document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a',
                customClass: { popup: 'premium-swal-popup' },
                width: '450px',
                padding: '2em'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = urlHapus;
                }
            })
        }
    </script>
</body>
</html>