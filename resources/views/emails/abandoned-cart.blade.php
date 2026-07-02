<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Impianmu Menunggumu! 🛒</title>
    <style>
        body {
            font-family: 'Montserrat', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #0f0f0f;
            color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .header {
            background: linear-gradient(135deg, #1f1a14 0%, #0f0f0f 100%);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 1px solid #D4AF37;
        }
        .brand {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 24px;
            font-weight: 700;
            color: #D4AF37;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 10px;
            letter-spacing: 4px;
            color: #888;
            text-transform: uppercase;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.8;
            font-size: 14px;
            color: #d1d1d1;
        }
        .title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 22px;
            color: #D4AF37;
            margin-bottom: 15px;
            text-align: center;
        }
        .intro-text {
            text-align: center;
            margin-bottom: 30px;
        }
        .product-list {
            margin: 20px 0;
        }
        .product-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #2a2a2a;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-img {
            width: 70px;
            height: 70px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid #2a2a2a;
            margin-right: 20px;
        }
        .product-details {
            flex-grow: 1;
        }
        .product-name {
            font-size: 14px;
            font-weight: 600;
            color: #f5f5f5;
            margin: 0 0 5px 0;
        }
        .product-category {
            font-size: 10px;
            color: #D4AF37;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 5px 0;
        }
        .product-price {
            font-size: 13px;
            color: #888;
            margin: 0;
        }
        .urgency-box {
            background-color: rgba(232, 76, 61, 0.05);
            border: 1px dashed #E84C3D;
            border-radius: 8px;
            padding: 15px 20px;
            margin: 30px 0;
            text-align: center;
            font-size: 13px;
            color: #e84c3d;
            font-weight: 600;
        }
        .btn-action {
            display: block;
            width: 250px;
            margin: 30px auto 0;
            padding: 15px 30px;
            background-color: #D4AF37;
            color: #111;
            text-align: center;
            text-decoration: none;
            font-weight: 800;
            font-size: 11px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 4px;
            transition: 0.3s;
            box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2);
        }
        .footer {
            background-color: #0f0f0f;
            padding: 30px;
            text-align: center;
            font-size: 11px;
            color: #666;
            border-top: 1px solid #2a2a2a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">ERNA THRIFTING</div>
            <div class="subtitle">Bespoke & Thrift</div>
        </div>
        <div class="content">
            <h2 class="title">Jangan Sampai Kehabisan! 🛒</h2>
            <p class="intro-text">Halo, <strong>{{ $user->name }}</strong>.<br>Kami melihat ada beberapa koleksi eksklusif yang masih tertinggal manis di keranjang belanjamu. Apakah kamu melupakannya?</p>

            <div class="product-list">
                @foreach($cartItems as $item)
                @php $p = $item->product; @endphp
                @if($p)
                <div class="product-item">
                    @if($p->gambar)
                        <img class="product-img" src="{{ asset($p->gambar) }}" alt="{{ $p->nama_produk }}">
                    @else
                        <div class="product-img" style="background:#222; display:flex; align-items:center; justify-content:center;"><span style="font-size:24px;">👕</span></div>
                    @endif
                    <div class="product-details">
                        <div class="product-category">{{ $p->kategori }}</div>
                        <h4 class="product-name">{{ $p->nama_produk }}</h4>
                        <p class="product-price">Rp {{ number_format($p->harga, 0, ',', '.') }}</p>
                    </div>
                </div>
                @endif
                @endforeach
            </div>

            <div class="urgency-box">
                ⚠️ PERINGATAN: Barang Thrifting bersifat UNIK & HANYA TERSEDIA 1 PCS. Jika orang lain menyelesaikan pembayaran terlebih dahulu, barang ini akan hilang selamanya!
            </div>

            <p style="text-align: center;">Selesaikan belanjamu sekarang secara instan menggunakan tombol di bawah:</p>
            <a href="{{ url('/keranjang') }}" class="btn-action">Checkout Sekarang</a>
        </div>
        <div class="footer">
            &copy; 2026 ERNA THRIFTING. All rights reserved.<br>
            Pontianak, Indonesia | hello@ernathrifting.com
        </div>
    </div>
</body>
</html>
