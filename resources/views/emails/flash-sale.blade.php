<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flash Sale</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0f0f0f; color: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .header { background: linear-gradient(135deg, #7f0000 0%, #1a0000 100%); padding: 40px 30px; text-align: center; border-bottom: 2px solid #E84C3D; }
        .brand { font-size: 24px; font-weight: 700; color: #fff; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 8px; }
        .flash-badge { background: #E84C3D; color: #fff; font-size: 11px; font-weight: 800; padding: 6px 20px; border-radius: 30px; letter-spacing: 2px; text-transform: uppercase; display: inline-block; margin-top: 10px; }
        .content { padding: 40px 30px; line-height: 1.8; font-size: 14px; color: #d1d1d1; }
        .greeting { font-size: 18px; color: #f5f5f5; margin-bottom: 12px; }
        .intro-text { font-size: 13px; color: #aaa; margin-bottom: 20px; line-height: 1.7; }
        .countdown-note { background: rgba(232,76,61,0.1); border: 1px solid rgba(232,76,61,0.3); border-radius: 8px; padding: 12px 16px; text-align: center; font-size: 12px; color: #E84C3D; font-weight: 700; margin-bottom: 25px; }
        .section-title { font-size: 13px; color: #888; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 1px solid #2a2a2a; }
        .product-card { background-color: #111; border: 1px solid #2a2a2a; border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
        .product-img { width: 100%; max-height: 220px; object-fit: cover; display: block; background-color: #fff; }
        .product-info { padding: 16px 20px; }
        .product-category { font-size: 9px; color: #E84C3D; text-transform: uppercase; letter-spacing: 2px; font-weight: 700; margin-bottom: 6px; }
        .product-name { font-size: 17px; font-weight: 700; color: #f5f5f5; margin-bottom: 10px; }
        .price-row { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 6px; }
        .price-flash { font-size: 20px; font-weight: 800; color: #E84C3D; }
        .price-original { font-size: 14px; color: #555; text-decoration: line-through; }
        .discount-badge { background: rgba(232,76,61,0.2); color: #E84C3D; border: 1px solid rgba(232,76,61,0.4); font-size: 10px; font-weight: 800; padding: 2px 8px; border-radius: 20px; }
        .stok-info { font-size: 11px; color: #888; margin-top: 4px; }
        .btn-product { display: inline-block; padding: 10px 20px; background-color: #E84C3D; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 11px; letter-spacing: 1px; text-transform: uppercase; border-radius: 6px; margin-top: 10px; }
        .footer { background-color: #0f0f0f; padding: 30px; text-align: center; font-size: 11px; color: #555; border-top: 1px solid #2a2a2a; }
        .footer a { color: #D4AF37; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">ERNA THRIFTING</div>
            <div class="flash-badge">⚡ Flash Sale: {{ $flashSale->nama_kampanye }}</div>
        </div>

        <div class="content">
            <p class="greeting">Halo, <strong>{{ $user->name }}</strong>! 👋</p>
            <p class="intro-text">
                Jangan sampai ketinggalan! Ada <strong>{{ $flashSaleItems->count() }} produk</strong> Flash Sale eksklusif
                dengan harga spesial yang hanya berlaku untuk waktu terbatas.
            </p>

            @if($flashSale->end_time)
            <div class="countdown-note">
                ⏰ Flash Sale berakhir: {{ \Carbon\Carbon::parse($flashSale->end_time)->format('d M Y, H:i') }} WIB
            </div>
            @endif

            <div class="section-title">Produk Flash Sale</div>

            @foreach($flashSaleItems as $item)
            @if($item->product)
            <div class="product-card">
                @if($item->product->gambar)
                <img src="{{ rtrim(config('app.url'), '/') . '/' . ltrim($item->product->gambar, '/') }}"
                     alt="{{ $item->product->nama_produk }}"
                     class="product-img">
                @endif
                <div class="product-info">
                    <div class="product-category">{{ $item->product->kategori ?? '' }} — Flash Sale</div>
                    <div class="product-name">{{ $item->product->nama_produk }}</div>
                    <div class="price-row">
                        <span class="price-flash">Rp {{ number_format($item->harga_diskon, 0, ',', '.') }}</span>
                        <span class="price-original">Rp {{ number_format($item->product->harga, 0, ',', '.') }}</span>
                        @php
                            $pct = $item->product->harga > 0
                                ? round((($item->product->harga - $item->harga_diskon) / $item->product->harga) * 100)
                                : 0;
                        @endphp
                        @if($pct > 0)
                        <span class="discount-badge">-{{ $pct }}%</span>
                        @endif
                    </div>
                    <div class="stok-info">⚠️ Kuota tersisa {{ $item->kuota_stok }} pcs</div>
                    <a href="{{ rtrim(config('app.url'), '/') }}/produk/detail/{{ $item->product->id }}" class="btn-product">
                        ⚡ Beli Sekarang
                    </a>
                </div>
            </div>
            @endif
            @endforeach

            <!-- CTA lihat semua -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 10px 0;">
                <tr>
                    <td align="center">
                        <a href="{{ rtrim(config('app.url'), '/') }}/flash-sale"
                           style="display: inline-block; padding: 14px 40px; background-color: #E84C3D; color: #ffffff; text-decoration: none; font-weight: 800; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; border-radius: 6px; text-align: center;">
                            LIHAT SEMUA FLASH SALE
                        </a>
                    </td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p style="font-size: 11px; color: #666; line-height: 1.6; font-style: italic; margin: 0 0 15px 0;">
                Kamu menerima email ini karena terdaftar sebagai pelanggan ERNA THRIFTING.
            </p>
            &copy; 2026 ERNA THRIFTING. All rights reserved.<br>
            Pontianak, Indonesia &nbsp;|&nbsp;
            <a href="{{ rtrim(config('app.url'), '/') }}">Kunjungi Toko</a> &nbsp;|&nbsp;
            <a href="{{ rtrim(config('app.url'), '/') }}/flash-sale">Lihat Flash Sale</a>
        </div>
    </div>
</body>
</html>
