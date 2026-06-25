@php
    $user = Auth::user();
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Membership VIP' }} | {{ $nama_toko }}</title>
    
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
        }

        body:not(.light-mode) { background: radial-gradient(circle at center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at center, #ffffff 0%, var(--bg-dark) 100%); }

        .vip-container {
            width: 100%;
            max-width: 850px;
            animation: eleganceIn 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes eleganceIn { to { transform: translateY(0); opacity: 1; } }
        @keyframes flash { from { opacity: 0.4; } to { opacity: 1; } }

        .vip-card-main {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
            transition: 0.4s ease;
        }

        body:not(.light-mode) .vip-card-main { box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 0 20px rgba(212, 175, 55, 0.02); border-color: rgba(212, 175, 55, 0.15); }
        body.light-mode .vip-card-main { box-shadow: 0 20px 50px rgba(0,0,0,0.05); }

        /* --- HEADER --- */
        .header {
            padding: 30px 40px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.4s ease;
        }

        .header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 1px;
        }

        .btn-close {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 22px;
            transition: 0.3s;
            background: none;
            border: none;
            cursor: pointer;
        }
        .btn-close:hover { color: var(--gold); transform: rotate(90deg); }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 40px;
            padding: 40px;
        }

        /* --- KARTU STATUS SAAT INI --- */
        .status-card {
            background: linear-gradient(135deg, #8b6b50, #5e463b);
            padding: 35px 25px;
            border-radius: 16px;
            color: white;
            position: relative;
            overflow: hidden;
            margin-bottom: 25px;
            border: 1px solid rgba(212, 175, 55, 0.2);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .status-card::after {
            content: '\f521';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: -15px;
            bottom: -15px;
            font-size: 80px;
            opacity: 0.15;
            transform: rotate(-15deg);
        }

        .status-title {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            font-weight: 600;
            opacity: 0.9;
        }

        .status-value {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .desc-text {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.8;
            transition: 0.4s ease;
        }

        /* --- KARTU PILIHAN PAKET --- */
        .package-card {
            background: var(--bg-dark);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 25px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }

        body.light-mode .package-card { background: rgba(0,0,0,0.02); }

        .package-card:hover {
            transform: translateY(-5px);
            border-color: var(--gold);
            background: rgba(212, 175, 55, 0.05);
        }

        .package-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .package-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: 1px;
            transition: 0.4s ease;
        }

        .package-duration {
            background: var(--border-color);
            color: var(--text-muted);
            font-size: 10px;
            font-weight: 700;
            padding: 5px 15px;
            border-radius: 20px;
            text-transform: uppercase;
            transition: 0.4s ease;
        }

        .package-price {
            font-family: 'Playfair Display', serif;
            font-size: 30px;
            color: var(--gold);
            font-weight: 700;
            margin-bottom: 25px;
        }

        .package-features {
            list-style: none;
            margin-bottom: 30px;
        }

        .package-features li {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.4s ease;
        }

        .package-features li i {
            color: var(--gold);
            font-size: 10px;
        }

        .btn-select {
            display: block;
            width: 100%;
            text-align: center;
            padding: 16px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-decoration: none;
            transition: 0.3s;
            cursor: pointer;
            border: 1px solid var(--gold);
            font-family: 'Montserrat', sans-serif;
        }

        .btn-silver { background: transparent; color: var(--gold); }
        .btn-silver:hover { background: var(--gold); color: #111; }

        .btn-gold { background: var(--gold); color: #111; }
        .btn-gold:hover { background: var(--gold-hover); transform: scale(1.02); }

        /* --- SWEETALERT CUSTOM --- */
        .premium-swal-popup { background: var(--bg-surface) !important; border: 1px solid var(--border-color) !important; border-radius: 16px !important; color: var(--text-main) !important; }
        .ecommerce-toast { border-radius: 12px !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }

        @media (max-width: 768px) {
            .content-grid { grid-template-columns: 1fr; padding: 30px 20px; }
            .header { padding: 25px 20px; }
            .vip-container { padding: 10px; }
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

    <div class="vip-container">
        <div class="vip-card-main">
            
            <div class="header">
                <h2>Pilih Paket VIP</h2>
                <a href="{{ url('/profile') }}" class="btn-close"><i class="fas fa-times"></i></a>
            </div>

            <div class="content-grid">
                
                <div class="left-side">
                    @php 
                        $vip = Auth::user()->vip_paket ?? 'REGULER'; 
                        $daysRemaining = null;
                        if ($vip !== 'REGULER' && Auth::user()->member_until) {
                            $expiryDate = \Carbon\Carbon::parse(Auth::user()->member_until);
                            $daysRemaining = \Carbon\Carbon::now()->startOfDay()->diffInDays($expiryDate->startOfDay(), false);
                        }
                    @endphp
                    <div class="status-card">
                        <p class="status-title">Status Member Saat Ini</p>
                        <div class="status-value">{{ $vip }}</div>
                        @if ($vip !== 'REGULER' && Auth::user()->member_until)
                            <div class="status-expiry" style="margin-top: 15px; font-size: 12px; font-weight: 500; display: flex; flex-direction: column; gap: 4px; border-top: 1px solid rgba(255,255,255,0.15); padding-top: 12px;">
                                <span style="opacity: 0.8;"><i class="far fa-calendar-alt" style="margin-right: 5px;"></i> Berlaku Hingga:</span>
                                <span style="font-weight: 700; font-size: 14px; color: #fff;">{{ \Carbon\Carbon::parse(Auth::user()->member_until)->locale('id')->translatedFormat('d F Y') }}</span>
                                @if ($daysRemaining !== null)
                                    @if ($daysRemaining > 0 && $daysRemaining <= 3)
                                        <span style="font-size: 10px; background: #e67e22; color: white; padding: 3px 10px; border-radius: 20px; align-self: flex-start; margin-top: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; animation: flash 1s infinite alternate;">
                                            <i class="fas fa-exclamation-triangle"></i> Sisa {{ $daysRemaining }} Hari Lagi!
                                        </span>
                                    @elseif ($daysRemaining == 0)
                                        <span style="font-size: 10px; background: #e74c3c; color: white; padding: 3px 10px; border-radius: 20px; align-self: flex-start; margin-top: 6px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; animation: flash 1s infinite alternate;">
                                            <i class="fas fa-exclamation-circle"></i> Habis Hari Ini!
                                        </span>
                                    @elseif ($daysRemaining > 3)
                                        <span style="font-size: 11px; background: rgba(255,255,255,0.2); padding: 3px 10px; border-radius: 20px; align-self: flex-start; margin-top: 6px; font-weight: 600;">
                                            <i class="far fa-clock"></i> Sisa {{ $daysRemaining }} Hari
                                        </span>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
                    <p class="desc-text">
                        Tingkatkan pengalaman berbelanja di <strong>{{ $nama_toko }}</strong>. Dapatkan akses koleksi lebih awal, diskon otomatis di setiap transaksi, dan fasilitas gratis ongkir eksklusif bagi member VIP kami.
                    </p>
                </div>

                <div class="right-side">
                    
                    <div class="package-card silver-card">
                        <div class="package-header">
                            <div class="package-name"><i class="fas fa-medal" style="color: #bdc3c7;"></i> Silver</div>
                            <div class="package-duration">1 Bulan</div>
                        </div>
                        <div class="package-price">Rp 50.000</div>
                        <ul class="package-features">
                            <li><i class="fas fa-check-double"></i> Gratis Ongkir maks Rp 20.000 (3x/Bulan)</li>
                            <li><i class="fas fa-check-double"></i> Akses koleksi baru 6 jam lebih awal</li>
                            <li><i class="fas fa-check-double"></i> Customer Service Prioritas</li>
                        </ul>
                        <a href="{{ url('/beli-membership/silver') }}" class="btn-select btn-silver" onclick="confirmVIP(event, 'SILVER', 50000)">Pilih Paket Silver</a>
                    </div>

                    <div class="package-card gold-card">
                        <div class="package-header">
                            <div class="package-name"><i class="fas fa-crown" style="color: var(--gold);"></i> Gold</div>
                            <div class="package-duration">6 Bulan</div>
                        </div>
                        <div class="package-price">Rp 200.000</div>
                        <ul class="package-features">
                            <li><i class="fas fa-check-double"></i> Gratis Ongkir tanpa batas (10x/Bulan)</li>
                            <li><i class="fas fa-check-double"></i> Diskon otomatis 5% setiap transaksi</li>
                            <li><i class="fas fa-check-double"></i> Akses koleksi baru 12 jam lebih awal</li>
                        </ul>
                        <a href="{{ url('/beli-membership/gold') }}" class="btn-select btn-gold" onclick="confirmVIP(event, 'GOLD', 200000)">Pilih Paket Gold</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmVIP(e, paket, harga) {
            e.preventDefault();
            const url = e.currentTarget.getAttribute('href');
            
            // Warna background dinamis untuk sweetalert menyesuaikan tema
            let swalBg = document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a';
            let swalColor = document.body.classList.contains('light-mode') ? '#111111' : '#ffffff';
            let btnCancel = document.body.classList.contains('light-mode') ? '#dddddd' : '#2a2a2a';

            Swal.fire({
                title: '<span style="color:var(--gold); font-family:\'Playfair Display\'">Konfirmasi Upgrade</span>',
                html: `Apakah Anda yakin ingin upgrade ke member <b>${paket}</b>?<br><br>Saldo Anda akan dipotong sebesar <b>Rp ${harga.toLocaleString('id-ID')}</b>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#D4AF37',
                cancelButtonColor: btnCancel,
                confirmButtonText: 'Ya, Upgrade Sekarang',
                cancelButtonText: 'Batal',
                background: swalBg,
                color: swalColor,
                customClass: {
                    popup: 'premium-swal-popup'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }
    </script>
</body>
</html>