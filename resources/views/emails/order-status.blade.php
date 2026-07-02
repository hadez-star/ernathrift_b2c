<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
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
            font-size: 20px;
            color: #D4AF37;
            margin-bottom: 20px;
            text-align: center;
        }
        .details-box {
            background-color: #111;
            border: 1px solid #2a2a2a;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
        }
        .details-row:last-child {
            margin-bottom: 0;
            border-top: 1px solid #2a2a2a;
            padding-top: 10px;
            font-weight: bold;
        }
        .btn-action {
            display: block;
            width: 200px;
            margin: 30px auto 0;
            padding: 15px 30px;
            background-color: #D4AF37;
            color: #111;
            text-align: center;
            text-decoration: none;
            font-weight: 700;
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            border-radius: 4px;
            transition: 0.3s;
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
            <h2 class="title">{{ $title }}</h2>
            <p>Halo, pelanggan setia ERNA THRIFTING.</p>
            <p>{{ $mailMessage }}</p>

            <div class="details-box">
                <div class="details-row">
                    <span style="color: #888;">Invoice:</span>
                    <span style="color: #f5f5f5; font-weight: bold;">{{ $order->invoice }}</span>
                </div>
                <div class="details-row">
                    <span style="color: #888;">Tanggal:</span>
                    <span style="color: #f5f5f5;">{{ $order->created_at->format('d M Y H:i') }} WIB</span>
                </div>
                <div class="details-row">
                    <span style="color: #888;">Alamat:</span>
                    <span style="color: #f5f5f5; text-align: right; max-width: 60%;">{{ $order->alamat_pengiriman }}</span>
                </div>
                <div class="details-row">
                    <span style="color: #888;">Status:</span>
                    <span style="color: #D4AF37; font-weight: bold;">{{ $order->status }}</span>
                </div>
                @if($order->no_resi)
                <div class="details-row">
                    <span style="color: #888;">No. Resi:</span>
                    <span style="color: #3498DB; font-weight: bold;">{{ $order->no_resi }}</span>
                </div>
                @endif
                <div class="details-row">
                    <span style="color: #888;">Total Pembayaran:</span>
                    <span style="color: #D4AF37;">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                </div>
            </div>

            <p style="text-align: center;">Anda dapat memantau status pesanan secara langsung di website kami:</p>
            <a href="{{ url('/riwayat-pesanan') }}" class="btn-action">Pantau Pesanan</a>
        </div>
        <div class="footer">
            &copy; 2026 ERNA THRIFTING. All rights reserved.<br>
            Pontianak, Indonesia | hello@ernathrifting.com
        </div>
    </div>
</body>
</html>
