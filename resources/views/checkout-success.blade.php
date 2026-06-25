@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil | {{ $nama_toko }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    @if($order->snap_token)
    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
    
    <style>
        :root {
            --gold: #D4AF37;
            --gold-hover: #bda038;
            --bg-dark: #0f0f0f;
            --bg-surface: #1a1a1a;
            --text-main: #f5f5f5;
            --text-muted: #888888;
            --border-color: #2a2a2a;
            --success: #2ecc71;
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
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
            transition: 0.4s;
        }

        body:not(.light-mode) { background: radial-gradient(circle at center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at center, #ffffff 0%, var(--bg-dark) 100%); }

        .success-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 30px;
            padding: 60px 40px;
            text-align: center;
            max-width: 500px;
            width: 90%;
            position: relative;
            box-shadow: 0 40px 100px rgba(0,0,0,0.5);
            animation: cardSlideIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            backdrop-filter: blur(10px);
        }

        @keyframes cardSlideIn {
            from { opacity: 0; transform: translateY(50px) scale(0.9); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .checkmark-wrapper {
            width: 100px;
            height: 100px;
            background: rgba(212, 175, 55, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            position: relative;
        }

        .checkmark-wrapper i {
            font-size: 50px;
            color: var(--gold);
            animation: checkPop 0.5s 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) both;
        }

        @keyframes checkPop {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .checkmark-wrapper::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border: 2px solid var(--gold);
            border-radius: 50%;
            animation: ringRipple 1.5s infinite;
        }

        @keyframes ringRipple {
            0% { transform: scale(1); opacity: 0.8; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        h1 { font-family: 'Playfair Display', serif; font-size: 32px; color: var(--gold); margin-bottom: 15px; }
        p { color: var(--text-muted); line-height: 1.6; margin-bottom: 30px; font-size: 14px; }

        .order-info {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 40px;
            text-align: left;
        }
        body.light-mode .order-info { background: rgba(0,0,0,0.02); }

        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 13px; }
        .info-row:last-child { margin-bottom: 0; border-top: 1px dashed var(--border-color); pt-10; margin-top: 10px; padding-top: 10px;}
        .info-label { color: var(--text-muted); }
        .info-value { font-weight: 700; color: var(--text-main); }

        .btn-group { display: flex; flex-direction: column; gap: 15px; }
        .btn {
            padding: 16px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-decoration: none;
            transition: 0.3s;
            display: block;
        }

        .btn-primary { background: var(--gold); color: #111; }
        .btn-primary:hover { background: var(--gold-hover); transform: translateY(-3px); box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3); }

        .btn-secondary { background: transparent; border: 1px solid var(--border-color); color: var(--text-muted); }
        .btn-secondary:hover { border-color: var(--gold); color: var(--gold); background: rgba(212, 175, 55, 0.05); }

        .confetti-canvas { position: fixed; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 9999; }
    </style>
</head>
<body>

    <script>
        if (localStorage.getItem('erna_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>

    <div class="success-card">
        <div class="checkmark-wrapper">
            <i class="fas {{ $order->status == 'Menunggu Pembayaran' ? 'fa-clock' : 'fa-check' }}"></i>
        </div>
        
        <h1>{{ $order->status == 'Menunggu Pembayaran' ? 'Pesanan Dibuat!' : 'Terima Kasih!' }}</h1>
        <p>
            @if($order->status == 'Menunggu Pembayaran')
                Pesanan Anda telah dibuat. Silakan selesaikan pembayaran melalui tombol di bawah.
            @else
                Pesanan Anda telah kami terima dan sedang diproses. Email konfirmasi telah dikirim ke alamat terdaftar Anda.
            @endif
        </p>
        
        <div class="order-info">
            <div class="info-row">
                <span class="info-label">No. Invoice</span>
                <span class="info-value">#{{ $order->invoice }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode Pembayaran</span>
                <span class="info-value">{{ $order->metode_pembayaran ?? 'Midtrans' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Pembayaran</span>
                <span class="info-value" style="color: var(--gold); font-size: 16px;">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="btn-group">
            @if($order->status == 'Menunggu Pembayaran' && $order->snap_token)
            <button onclick="bayarSekarang('{{ $order->snap_token }}', {{ $order->id }})" class="btn btn-primary">
                <i class="fas fa-credit-card"></i> &nbsp;Selesaikan Pembayaran
            </button>
            @endif
            <a href="{{ url('/pesanan/lacak/'.$order->id) }}" class="btn {{ $order->status == 'Menunggu Pembayaran' ? 'btn-secondary' : 'btn-primary' }}">Lacak Pesanan Saya</a>
            <a href="{{ url('/') }}" class="btn btn-secondary">Kembali Berbelanja</a>
        </div>
    </div>

    <script>
        // CELEBRATION ANIMATION
        window.onload = function() {
            @if($order->status !== 'Menunggu Pembayaran')
            var duration = 3 * 1000;
            var end = Date.now() + duration;

            (function frame() {
                confetti({
                    particleCount: 3,
                    angle: 60,
                    spread: 55,
                    origin: { x: 0 },
                    colors: ['#D4AF37', '#ffffff', '#bda038']
                });
                confetti({
                    particleCount: 3,
                    angle: 120,
                    spread: 55,
                    origin: { x: 1 },
                    colors: ['#D4AF37', '#ffffff', '#bda038']
                });

                if (Date.now() < end) {
                    requestAnimationFrame(frame);
                }
            }());

            // Burst effect
            confetti({
                particleCount: 150,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#D4AF37', '#ffffff', '#bda038']
            });
            @endif
        };

        @if($order->snap_token)
        function bayarSekarang(snapToken, orderId) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    location.reload();
                },
                onPending: function(result) {
                    location.reload();
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba lagi dari halaman lacak pesanan.');
                },
                onClose: function() {}
            });
        }
        @endif
    </script>
</body>
</html>
