<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ada Koleksi Baru Untukmu</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
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
            font-size: 24px;
            font-weight: 700;
            color: #D4AF37;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 8px;
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
        .greeting {
            font-size: 18px;
            color: #f5f5f5;
            margin-bottom: 12px;
        }
        .intro-text {
            font-size: 13px;
            color: #aaa;
            margin-bottom: 30px;
            line-height: 1.7;
        }
        .product-card {
            background-color: #111;
            border: 1px solid #2a2a2a;
            border-radius: 12px;
            overflow: hidden;
            margin: 20px 0 30px;
        }
        .product-img {
            width: 100%;
            max-height: 280px;
            object-fit: cover;
            display: block;
            background-color: #fff;
        }
        .product-info {
            padding: 20px 24px;
        }
        .product-category {
            font-size: 9px;
            color: #D4AF37;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .product-name {
            font-size: 20px;
            font-weight: 700;
            color: #f5f5f5;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .product-desc {
            font-size: 12px;
            color: #888;
            line-height: 1.6;
            margin-bottom: 16px;
        }
        .product-price {
            font-size: 22px;
            font-weight: 700;
            color: #D4AF37;
        }
        .badge-tersedia {
            display: inline-block;
            background: rgba(46,204,113,0.15);
            color: #2ECC71;
            border: 1px solid rgba(46,204,113,0.3);
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-left: 10px;
            vertical-align: middle;
        }
        .btn-cta {
            display: block;
            width: 220px;
            margin: 30px auto 10px;
            padding: 16px 30px;
            background-color: #D4AF37;
            color: #111;
            text-align: center;
            text-decoration: none;
            font-weight: 800;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 6px;
        }
        .urgency-note {
            text-align: center;
            font-size: 11px;
            color: #E84C3D;
            font-weight: 600;
            margin-top: 10px;
            letter-spacing: 0.5px;
        }
        .urgency-note i {
            margin-right: 5px;
        }
        .divider {
            border: none;
            border-top: 1px solid #2a2a2a;
            margin: 30px 0;
        }
        .why-note {
            font-size: 11px;
            color: #555;
            text-align: center;
            line-height: 1.6;
            font-style: italic;
        }
        .footer {
            background-color: #0f0f0f;
            padding: 30px;
            text-align: center;
            font-size: 11px;
            color: #555;
            border-top: 1px solid #2a2a2a;
        }
        .footer a {
            color: #D4AF37;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="brand">ERNA THRIFTING</div>
            <div class="subtitle">Bespoke &amp; Thrift</div>
        </div>

        <!-- Body -->
        <div class="content">
            <p class="greeting">Halo, <strong>{{ $user->name }}</strong>! 👋</p>
            <p class="intro-text">
                Karena kamu pernah membeli dari kategori <strong style="color: #D4AF37;">{{ $product->kategori }}</strong>,
                kami pikir kamu pasti suka dengan koleksi terbaru ini.
                @if($product->stok > 1)
                    Stok tersisa {{ $product->stok }} pcs — jangan sampai kehabisan!
                @else
                    Produk thrift hanya ada 1 pcs — jangan sampai kehabisan!
                @endif
            </p>

            <!-- Product Card -->
            <div class="product-card">
                @if($product->gambar)
                <img
                    src="{{ rtrim(config('app.url'), '/') . '/' . ltrim($product->gambar, '/') }}"
                    alt="{{ $product->nama_produk }}"
                    class="product-img"
                >
                @endif
                <div class="product-info">
                    <div class="product-category">{{ $product->kategori }}</div>
                    <div class="product-name">
                        {{ $product->nama_produk }}
                        <span class="badge-tersedia">✓ Tersedia</span>
                    </div>
                    @if($product->deskripsi)
                    <div class="product-desc">
                        {{ Str::limit(strip_tags($product->deskripsi), 120) }}
                    </div>
                    @endif
                    <div class="product-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- CTA Button -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0 10px;">
                <tr>
                    <td align="center">
                        <a href="{{ rtrim(config('app.url'), '/') }}/produk/detail/{{ $product->id }}"
                           style="display: inline-block; padding: 16px 40px; background-color: #D4AF37; color: #111111; text-decoration: none; font-weight: 800; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; border-radius: 6px; mso-padding-alt: 0; text-align: center;">
                            LIHAT &amp; BELI SEKARANG
                        </a>
                    </td>
                </tr>
            </table>
            <p class="urgency-note">
                ⚠️ Stok tersisa {{ $product->stok }} pcs. Segera pesan sebelum kehabisan!
            </p>

            <hr class="divider">
            <p class="why-note">
                Kamu menerima email ini karena kamu pernah berbelanja di kategori <strong>{{ $product->kategori }}</strong>
                di ERNA THRIFTING. Kami hanya mengirimkan rekomendasi yang relevan dengan riwayat belanjamu.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; 2026 ERNA THRIFTING. All rights reserved.<br>
            Pontianak, Indonesia &nbsp;|&nbsp;
            <a href="{{ url('/') }}">Kunjungi Toko</a> &nbsp;|&nbsp;
            <a href="{{ url('/riwayat-pesanan') }}">Riwayat Pesanan</a>
        </div>
    </div>
</body>
</html>
