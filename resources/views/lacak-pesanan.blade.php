@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lacak Pesanan | {{ $nama_toko }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            --info: #3498db;
            --danger: #e74c3c;
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
            transition: 0.4s;
        }

        body:not(.light-mode) { background: radial-gradient(circle at top center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at top center, #ffffff 0%, var(--bg-dark) 100%); }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px 20px;
            animation: eleganceIn 0.8s ease forwards;
        }

        @keyframes eleganceIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* --- HEADER NAVBAR --- */
        .header-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }
        .btn-back {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }
        .btn-back:hover { color: var(--gold); transform: translateX(-5px); }

        /* --- TRACK CARD --- */
        .track-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            position: relative;
            box-shadow: 0 30px 60px rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
        }
        body.light-mode .track-card { box-shadow: 0 20px 40px rgba(0,0,0,0.05); }

        .header-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            padding-bottom: 30px;
            border-bottom: 1px solid var(--border-color);
        }

        .order-id { font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700; color: var(--gold); margin-bottom: 5px; }
        .order-date { font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .status-badge { display: inline-block; padding: 8px 20px; border-radius: 30px; background: rgba(212, 175, 55, 0.1); color: var(--gold); font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; border: 1px solid rgba(212, 175, 55, 0.2); }

        /* --- HORIZONTAL PROGRESS BAR --- */
        .progress-tracker {
            display: flex;
            justify-content: space-between;
            margin-bottom: 60px;
            position: relative;
            padding: 0 10px;
        }
        .progress-tracker::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 50px;
            right: 50px;
            height: 2px;
            background: var(--border-color);
            z-index: 1;
        }
        .progress-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }
        .step-icon {
            width: 40px;
            height: 40px;
            background: var(--bg-surface);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: var(--text-muted);
            transition: 0.4s;
            font-size: 14px;
        }
        .step-label {
            font-size: 10px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.4s;
        }

        .progress-step.completed .step-icon { border-color: var(--success); color: var(--success); background: var(--bg-surface); }
        .progress-step.completed .step-label { color: var(--success); }
        
        .progress-step.active .step-icon { 
            border-color: var(--gold); 
            color: #111; 
            background: var(--gold); 
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.4);
            animation: iconPulse 2s infinite;
        }
        .progress-step.active .step-label { color: var(--gold); }

        @keyframes iconPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        /* --- VERTICAL TIMELINE --- */
        .timeline-section {
            margin-top: 40px;
            padding-top: 20px;
        }
        .timeline-title-main {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 30px;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .timeline-title-main::after { content: ''; flex: 1; height: 1px; background: var(--border-color); }

        .timeline {
            position: relative;
            padding-left: 50px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 5px;
            bottom: 5px;
            width: 1px;
            background: var(--border-color);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 40px;
        }
        .timeline-item:last-child { margin-bottom: 0; }

        .timeline-marker {
            position: absolute;
            left: -42px;
            top: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--bg-surface);
            border: 2px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transition: 0.3s;
        }
        .timeline-marker i { font-size: 10px; color: var(--text-muted); }

        .timeline-item.active .timeline-marker { border-color: var(--gold); background: var(--gold); box-shadow: 0 0 10px rgba(212, 175, 55, 0.3); }
        .timeline-item.active .timeline-marker i { color: #111; }
        
        .timeline-item.completed .timeline-marker { border-color: var(--success); background: var(--success); }
        .timeline-item.completed .timeline-marker i { color: #fff; }

        .timeline-content {
            padding-top: 2px;
            opacity: 0.5;
            transition: 0.4s;
        }
        .timeline-item.active .timeline-content,
        .timeline-item.completed .timeline-content { opacity: 1; }

        .timeline-h { font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 5px; }
        .timeline-p { font-size: 13px; color: var(--text-muted); line-height: 1.6; }
        .timeline-date { font-size: 10px; color: var(--gold); font-weight: 700; text-transform: uppercase; margin-top: 8px; display: block; letter-spacing: 1px; }

        /* --- RESI BOX --- */
        .resi-card {
            margin-top: 40px;
            background: rgba(212, 175, 55, 0.03);
            border: 1px dashed var(--gold);
            border-radius: 16px;
            padding: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .resi-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; }
        .resi-number { font-family: 'Montserrat', sans-serif; font-size: 20px; font-weight: 800; color: var(--gold); letter-spacing: 2px; }
        .btn-copy-resi { background: none; border: 1px solid var(--gold); color: var(--gold); padding: 5px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .btn-copy-resi:hover { background: var(--gold); color: #111; }

        @media (max-width: 600px) {
            .track-card { padding: 30px 20px; }
            .order-id { font-size: 22px; }
            .progress-tracker { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
            .progress-tracker::before { display: none; }
            .progress-step { display: flex; align-items: center; text-align: left; gap: 15px; }
            .step-icon { margin: 0; }
        }
    </style>
</head>
<body>

    <script>
        if (localStorage.getItem('erna_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>

    <div class="container">
        <header class="header-nav">
            <a href="{{ url('/riwayat-pesanan') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
            </a>
            @include('components.notif-bell')
        </header>

        <div class="track-card">
            <div class="header-info">
                <div>
                    <div class="order-id">#{{ $order->invoice }}</div>
                    <div class="order-date">Transaksi pada {{ $order->created_at->format('d M Y, H:i') }} WIB</div>
                </div>
                <div class="status-badge" style="{{ $order->status == 'Menunggu Pembayaran' ? 'background: rgba(231, 76, 60, 0.1); color: var(--danger); border-color: rgba(231, 76, 60, 0.2);' : '' }}">
                    {{ $order->status }}
                </div>
            </div>

            {{-- PAYMENT PENDING (For Midtrans Orders) --}}
            @if($order->status == 'Menunggu Pembayaran')
            <div style="background: rgba(212, 175, 55, 0.05); border: 1px solid var(--gold); border-radius: 16px; padding: 25px; margin-bottom: 40px; animation: pulse 2s infinite;">
                <h4 style="font-family: 'Playfair Display', serif; color: var(--gold); margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-exclamation-circle"></i> Selesaikan Pembayaran
                </h4>
                <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">
                    Pesanan Anda sebesar <strong style="color: var(--gold); font-size: 18px;">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong> belum dibayar.<br>
                    Segera selesaikan pembayaran sebelum pesanan otomatis dibatalkan.
                </p>

                @if($order->snap_token)
                <button id="btn-bayar-lagi" onclick="bayarSekarang('{{ $order->snap_token }}', {{ $order->id }})"
                    style="background: var(--gold); color: #111; border: none; padding: 14px 30px; border-radius: 10px; font-size: 12px; font-weight: 800; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; display: inline-block;">
                    <i class="fas fa-credit-card"></i> &nbsp;Bayar Sekarang
                </button>
                @else
                <p style="font-size: 12px; color: var(--text-muted);">Silakan hubungi admin jika membutuhkan bantuan.</p>
                @endif
            </div>
            <style>
                @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.2); } 70% { box-shadow: 0 0 0 15px rgba(212, 175, 55, 0); } 100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); } }
            </style>
            @endif

            @php
                $status = $order->status;
                $steps = [
                    'Menunggu Pembayaran' => 0,
                    'Tertunda' => 0,
                    'Dikemas' => 1,
                    'Dikirim' => 2,
                    'Selesai' => 3
                ];
                $currentStep = $steps[$status] ?? 0;
            @endphp

            {{-- Horizontal Progress --}}
            <div class="progress-tracker">
                <div class="progress-step {{ $currentStep >= 0 ? ($currentStep == 0 ? 'active' : 'completed') : '' }}">
                    <div class="step-icon"><i class="fas fa-wallet"></i></div>
                    <div class="step-label">Dibayar</div>
                </div>
                <div class="progress-step {{ $currentStep >= 1 ? ($currentStep == 1 ? 'active' : 'completed') : '' }}">
                    <div class="step-icon"><i class="fas fa-box-open"></i></div>
                    <div class="step-label">Dikemas</div>
                </div>
                <div class="progress-step {{ $currentStep >= 2 ? ($currentStep == 2 ? 'active' : 'completed') : '' }}">
                    <div class="step-icon"><i class="fas fa-shipping-fast"></i></div>
                    <div class="step-label">Dikirim</div>
                </div>
                <div class="progress-step {{ $currentStep >= 3 ? 'active' : '' }}">
                    <div class="step-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="step-label">Selesai</div>
                </div>
            </div>

            @if($order->no_resi)
            <div class="resi-card">
                <div>
                    <span class="resi-label">Nomor Resi Pengiriman</span>
                    <div class="resi-number">{{ $order->no_resi }}</div>
                </div>
                <button class="btn-copy-resi" onclick="copyResi('{{ $order->no_resi }}')">SALIN RESI</button>
            </div>
            @endif

            <div class="timeline-section">
                <h3 class="timeline-title-main">Riwayat Perjalanan Paket</h3>
                
                <div class="timeline">
                    {{-- Pesanan Selesai --}}
                    @if($currentStep >= 3)
                    <div class="timeline-item completed">
                        <div class="timeline-marker"><i class="fas fa-check"></i></div>
                        <div class="timeline-content">
                            <div class="timeline-h">Pesanan Selesai</div>
                            <div class="timeline-p">Paket telah diterima dengan baik. Terima kasih telah berbelanja di {{ $nama_toko }}!</div>
                            <span class="timeline-date">{{ $order->updated_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>
                    @endif

                    {{-- Sedang Dikirim --}}
                    @if($currentStep >= 2)
                    <div class="timeline-item {{ $currentStep == 2 ? 'active' : 'completed' }}">
                        <div class="timeline-marker"><i class="fas fa-truck"></i></div>
                        <div class="timeline-content">
                            <div class="timeline-h">Pesanan Sedang Dikirim</div>
                            <div class="timeline-p">Paket Anda sudah berada di kurir. Pantau terus pergerakannya menggunakan nomor resi di atas.</div>
                            <span class="timeline-date">{{ $order->updated_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>
                    @endif

                    {{-- Sedang Dikemas --}}
                    @if($currentStep >= 1)
                    <div class="timeline-item {{ $currentStep == 1 ? 'active' : 'completed' }}">
                        <div class="timeline-marker"><i class="fas fa-box"></i></div>
                        <div class="timeline-content">
                            <div class="timeline-h">Pesanan Sedang Dikemas</div>
                            <div class="timeline-p">Penjual sedang menyiapkan barang dan melakukan pengecekan kualitas terakhir.</div>
                            <span class="timeline-date">{{ $order->updated_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>
                    @endif

                    {{-- Pembayaran Berhasil --}}
                    <div class="timeline-item {{ $currentStep == 0 ? 'active' : 'completed' }}">
                        <div class="timeline-marker"><i class="fas fa-credit-card"></i></div>
                        <div class="timeline-content">
                            <div class="timeline-h">Pembayaran Berhasil</div>
                            <div class="timeline-p">Kami telah memverifikasi pembayaran Anda. Pesanan akan segera diteruskan ke bagian gudang.</div>
                            <span class="timeline-date">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    {{-- Pesanan Dibuat --}}
                    <div class="timeline-item completed">
                        <div class="timeline-marker"><i class="fas fa-file-invoice"></i></div>
                        <div class="timeline-content">
                            <div class="timeline-h">Pesanan Dibuat</div>
                            <div class="timeline-p">Invoice #{{ $order->invoice }} telah diterbitkan oleh sistem {{ $nama_toko }}.</div>
                            <span class="timeline-date">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        function copyResi(text) {
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Nomor resi berhasil disalin!',
                    showConfirmButton: false,
                    timer: 2000,
                    background: 'var(--bg-surface)',
                    color: 'var(--text-main)',
                    iconColor: '#D4AF37'
                });
            });
        }

        function bayarSekarang(snapToken, orderId) {
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    fetch('/checkout/payment-success/' + orderId, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ payment_type: result.payment_type })
                    }).finally(() => {
                        window.location.href = "{{ url('/checkout/success') }}/" + orderId;
                    });
                },
                onPending: function(result) {
                    location.reload();
                },
                onError: function(result) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan. Silakan coba lagi.',
                        confirmButtonColor: '#D4AF37'
                    });
                },
                onClose: function() {
                    // user menutup popup, tidak perlu redirect
                }
            });
        }
    </script>
</body>
</html>
