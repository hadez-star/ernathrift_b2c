@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Voucher | {{ $nama_toko }}</title>
    
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
            max-width: 1100px;
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

        /* Voucher Grid */
        .voucher-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
        }

        .voucher-card {
            background: linear-gradient(135deg, var(--bg-surface), var(--bg-dark));
            border: 1px solid var(--border-color);
            border-radius: 20px;
            overflow: hidden;
            position: relative;
            display: flex;
            transition: 0.4s ease;
        }

        .voucher-card:hover {
            transform: translateY(-5px);
            border-color: var(--gold);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.1);
        }

        .voucher-left {
            background: var(--gold);
            width: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            color: #111;
            padding: 20px;
            position: relative;
        }

        /* Zigzag edge */
        .voucher-left::after {
            content: "";
            position: absolute;
            top: 0;
            right: -10px;
            width: 20px;
            height: 100%;
            background-image: radial-gradient(circle at 10px 10px, transparent 10px, var(--gold) 10px);
            background-size: 20px 20px;
            z-index: 1;
        }

        .voucher-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .voucher-type {
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            transform: rotate(-90deg);
            white-space: nowrap;
        }

        .voucher-right {
            flex: 1;
            padding: 30px;
            padding-left: 40px;
        }

        .voucher-amount {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 5px;
        }

        .voucher-min-spend {
            font-size: 11px;
            color: var(--text-muted);
            margin-bottom: 20px;
        }

        .voucher-code-wrap {
            background: rgba(255,255,255,0.03);
            border: 1px dashed var(--border-color);
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .voucher-code {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 2px;
        }

        .btn-claim {
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
        }

        .btn-claim:hover {
            color: var(--gold);
            transform: scale(1.1);
        }

        .voucher-expiry {
            font-size: 10px;
            color: #555;
            margin-top: 15px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
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

        @media (max-width: 600px) {
            .voucher-card { flex-direction: column; }
            .voucher-left { width: 100%; height: 80px; flex-direction: row; }
            .voucher-left::after { display: none; }
            .voucher-type { transform: none; margin-left: 20px; }
            .voucher-right { padding: 25px; }
        }
    </style>
</head>
<body class="{{ (isset($_COOKIE['erna_theme']) && $_COOKIE['erna_theme'] == 'light') ? 'light-mode' : '' }}">

    <script>
        if (localStorage.getItem('erna_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>

    <a href="{{ url('/') }}" class="nav-back">
        <i class="fas fa-arrow-left"></i> Kembali ke Beranda
    </a>

    <div class="container">
        <header class="page-header">
            <h1 class="page-title">Pusat Voucher</h1>
            <p class="page-subtitle">Klaim Penawaran Eksklusif Untuk Belanja Anda</p>
        </header>

        <div class="voucher-grid">
            @forelse($vouchers as $v)
            <div class="voucher-card">
                <div class="voucher-left">
                    <div class="voucher-icon"><i class="fas fa-ticket-alt"></i></div>
                    <div class="voucher-type">Exclusive</div>
                </div>
                <div class="voucher-right">
                    <div class="voucher-amount">
                        @if($v->type == 'percent')
                            Potongan {{ $v->reward_amount }}%
                        @else
                            Diskon Rp {{ number_format($v->reward_amount, 0, ',', '.') }}
                        @endif
                    </div>
                    <div class="voucher-min-spend">Minimal Belanja: Rp {{ number_format($v->min_spend, 0, ',', '.') }}</div>
                    
                    <div class="voucher-code-wrap">
                        <span class="voucher-code" id="code-{{ $v->id }}">{{ $v->code }}</span>
                        <button class="btn-claim" onclick="copyCode('{{ $v->code }}', {{ $v->id }})" title="Salin Kode">
                            <i class="far fa-copy" id="icon-{{ $v->id }}"></i>
                        </button>
                    </div>
                    
                    <div class="voucher-expiry">Berlaku hingga: {{ \Carbon\Carbon::parse($v->valid_until)->format('d F Y') }}</div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                <i class="fas fa-ghost" style="font-size: 50px; color: var(--border-color); margin-bottom: 20px;"></i>
                <h3 style="font-family: 'Playfair Display', serif; font-size: 24px;">Belum Ada Voucher Tersedia</h3>
                <p style="color: var(--text-muted);">Cek kembali nanti untuk penawaran menarik lainnya.</p>
            </div>
            @endforelse
        </div>
    </div>

    <script>
        function copyCode(code, id) {
            navigator.clipboard.writeText(code).then(() => {
                const icon = document.getElementById(`icon-${id}`);
                icon.classList.replace('far', 'fas');
                icon.classList.replace('fa-copy', 'fa-check');
                icon.style.color = '#2ecc71';
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    icon: 'success',
                    title: 'Kode Voucher Disalin!',
                    background: document.body.classList.contains('light-mode') ? '#fff' : '#1a1a1a',
                    color: document.body.classList.contains('light-mode') ? '#111' : '#fff'
                });

                setTimeout(() => {
                    icon.classList.replace('fas', 'far');
                    icon.classList.replace('fa-check', 'fa-copy');
                    icon.style.color = '';
                }, 3000);
            });
        }
    </script>
</body>
</html>
