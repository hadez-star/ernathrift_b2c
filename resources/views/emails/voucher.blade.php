<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voucher Diskon</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #0f0f0f; color: #f5f5f5; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .header { background: linear-gradient(135deg, #1f1a14 0%, #0f0f0f 100%); padding: 40px 30px; text-align: center; border-bottom: 1px solid #D4AF37; }
        .brand { font-size: 24px; font-weight: 700; color: #D4AF37; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 8px; }
        .subtitle { font-size: 10px; letter-spacing: 4px; color: #888; text-transform: uppercase; }
        .content { padding: 40px 30px; line-height: 1.8; font-size: 14px; color: #d1d1d1; }
        .greeting { font-size: 18px; color: #f5f5f5; margin-bottom: 12px; }
        .intro-text { font-size: 13px; color: #aaa; margin-bottom: 30px; line-height: 1.7; }
        .voucher-card {
            background: linear-gradient(135deg, #1f1a0a 0%, #111 100%);
            border: 2px dashed #D4AF37;
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            margin: 20px 0 30px;
            position: relative;
        }
        .voucher-label { font-size: 10px; color: #888; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; }
        .voucher-code {
            font-size: 32px;
            font-weight: 900;
            color: #D4AF37;
            letter-spacing: 6px;
            font-family: 'Courier New', monospace;
            background: rgba(212,175,55,0.1);
            padding: 12px 24px;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 15px;
            border: 1px solid rgba(212,175,55,0.3);
        }
        .voucher-value { font-size: 22px; font-weight: 700; color: #f5f5f5; margin-bottom: 8px; }
        .voucher-terms { font-size: 11px; color: #666; line-height: 1.6; margin-top: 15px; }
        .voucher-expiry { font-size: 12px; color: #E84C3D; font-weight: 600; margin-top: 10px; }
        .footer { background-color: #0f0f0f; padding: 30px; text-align: center; font-size: 11px; color: #555; border-top: 1px solid #2a2a2a; }
        .footer a { color: #D4AF37; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="brand">ERNA THRIFTING</div>
            <div class="subtitle">Bespoke &amp; Thrift</div>
        </div>

        <div class="content">
            <p class="greeting">Halo, <strong>{{ $user->name }}</strong>! 🎁</p>
            <p class="intro-text">
                Kami punya hadiah spesial untukmu! Gunakan voucher eksklusif di bawah ini
                untuk mendapatkan diskon pada pembelian berikutnya.
            </p>

            <!-- Voucher Card -->
            <div class="voucher-card">
                <div class="voucher-label">Voucher Diskon Eksklusif</div>
                <div class="voucher-code">{{ $voucher->code }}</div>
                <div class="voucher-value">
                    @if($voucher->type === 'percent')
                        Diskon {{ $voucher->reward_amount }}%
                    @else
                        Diskon Rp {{ number_format($voucher->reward_amount, 0, ',', '.') }}
                    @endif
                </div>
                <div class="voucher-terms">
                    @if($voucher->min_spend > 0)
                        Minimum pembelian: Rp {{ number_format($voucher->min_spend, 0, ',', '.') }}<br>
                    @endif
                    @if($voucher->limit > 0)
                        Kuota: {{ $voucher->limit }} penggunaan tersisa
                    @endif
                </div>
                @if($voucher->valid_until)
                <div class="voucher-expiry">
                    ⏰ Berlaku hingga: {{ \Carbon\Carbon::parse($voucher->valid_until)->format('d M Y, H:i') }} WIB
                </div>
                @endif
            </div>

            <!-- CTA -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 20px 0 10px;">
                <tr>
                    <td align="center">
                        <a href="{{ rtrim(config('app.url'), '/') }}/katalog/semua"
                           style="display: inline-block; padding: 16px 40px; background-color: #D4AF37; color: #111111; text-decoration: none; font-weight: 800; font-size: 13px; letter-spacing: 2px; text-transform: uppercase; border-radius: 6px; text-align: center;">
                            BELANJA SEKARANG
                        </a>
                    </td>
                </tr>
            </table>
            <p style="text-align: center; font-size: 12px; color: #888; margin-top: 15px;">
                Masukkan kode voucher saat checkout untuk menggunakan diskon ini.
            </p>
        </div>

        <div class="footer">
            <p style="font-size: 11px; color: #666; line-height: 1.6; font-style: italic; margin: 0 0 15px 0;">
                Kamu menerima email ini karena terdaftar sebagai pelanggan ERNA THRIFTING.
            </p>
            &copy; 2026 ERNA THRIFTING. All rights reserved.<br>
            Pontianak, Indonesia &nbsp;|&nbsp;
            <a href="{{ rtrim(config('app.url'), '/') }}">Kunjungi Toko</a> &nbsp;|&nbsp;
            <a href="{{ rtrim(config('app.url'), '/') }}/vouchers">Lihat Voucher</a>
        </div>
    </div>
</body>
</html>
