@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
    $user = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Saldo ERNA Pay' }} | {{ $nama_toko }}</title>
    
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
            padding: 20px;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        body:not(.light-mode) { background: radial-gradient(circle at center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at center, #ffffff 0%, var(--bg-dark) 100%); }

        .saldo-container {
            width: 100%;
            max-width: 480px;
            animation: eleganceIn 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes eleganceIn { to { transform: translateY(0); opacity: 1; } }

        .saldo-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            position: relative;
            transition: 0.4s ease;
        }

        body:not(.light-mode) .saldo-card { box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 0 20px rgba(212, 175, 55, 0.02); border-color: rgba(212, 175, 55, 0.15); }
        body.light-mode .saldo-card { box-shadow: 0 20px 50px rgba(0,0,0,0.05); }

        /* --- HEADER --- */
        .card-header {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 35px;
            position: relative;
        }

        .btn-back {
            position: absolute;
            left: 0;
            color: var(--text-muted);
            font-size: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(136, 136, 136, 0.05);
            border: 1px solid var(--border-color);
        }
        .btn-back:hover { color: var(--gold); background: rgba(212, 175, 55, 0.1); border-color: rgba(212, 175, 55, 0.3); transform: translateX(-5px); }

        .card-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 1px;
        }

        /* --- SALDO DISPLAY --- */
        .balance-box {
            border: 1px solid rgba(212, 175, 55, 0.2);
            padding: 30px 20px;
            border-radius: 20px;
            margin-bottom: 35px;
            text-align: center;
            transition: 0.4s ease;
        }

        body:not(.light-mode) .balance-box { 
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.05), rgba(0, 0, 0, 0.4));
            box-shadow: 0 10px 20px rgba(0,0,0,0.2); 
        }
        body.light-mode .balance-box { 
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.02), rgba(0, 0, 0, 0.03));
            box-shadow: 0 10px 20px rgba(0,0,0,0.02); 
        }

        .balance-label {
            font-size: 10px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
            font-weight: 700;
            display: block;
            transition: 0.4s ease;
        }

        .balance-amount {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            color: var(--gold);
            font-weight: 700;
            text-shadow: 0 0 15px rgba(212, 175, 55, 0.1);
        }

        /* --- FORM TOP UP --- */
        .form-group { text-align: left; margin-bottom: 25px; }
        .form-group label { display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; transition: 0.4s ease; }
        
        .input-wrapper { position: relative; }
        .input-wrapper span { 
            position: absolute; 
            left: 20px; 
            top: 50%; 
            transform: translateY(-50%); 
            font-weight: 700; 
            color: var(--gold); 
            font-family: 'Playfair Display', serif;
            font-size: 16px;
        }

        .form-control { 
            width: 100%; 
            padding: 16px 15px 16px 55px; 
            background: transparent;
            border: 1px solid var(--border-color); 
            border-radius: 12px; 
            font-size: 16px; 
            font-weight: 600; 
            color: var(--text-main); 
            outline: none; 
            transition: 0.3s; 
            font-family: 'Montserrat', sans-serif;
        }
        .form-control:focus { border-color: var(--gold); }

        /* --- METODE PEMBAYARAN (RADIO CARDS) --- */
        .payment-methods {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 30px;
        }

        .payment-label { cursor: pointer; display: block; }
        .payment-label input { display: none; }
        
        .payment-card {
            background: transparent;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 20px 15px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            height: 100%;
        }

        body.light-mode .payment-card { background: rgba(0,0,0,0.02); }

        .payment-card i.main-icon { font-size: 24px; color: var(--text-muted); margin-bottom: 12px; display: block; transition: 0.4s ease; }
        .payment-card span { font-size: 12px; font-weight: 600; color: var(--text-muted); display: block; transition: 0.4s ease; letter-spacing: 0.5px; }
        
        .check-icon {
            position: absolute; top: 12px; right: 12px; color: var(--gold); font-size: 14px;
            opacity: 0; transform: scale(0.5); transition: 0.3s;
        }

        .payment-card:hover { border-color: rgba(212, 175, 55, 0.5); }
        
        .payment-label input:checked + .payment-card {
            border-color: var(--gold);
            background: rgba(212, 175, 55, 0.05);
            box-shadow: 0 5px 20px rgba(212, 175, 55, 0.1);
        }
        .payment-label input:checked + .payment-card i.main-icon { color: var(--gold); transform: scale(1.1); }
        .payment-label input:checked + .payment-card span { color: var(--gold); }
        .payment-label input:checked + .payment-card .check-icon { opacity: 1; transform: scale(1); }

        /* --- TOMBOL SUBMIT --- */
        .btn-submit { 
            width: 100%; 
            background: var(--gold); 
            color: #111; 
            border: none; 
            padding: 18px; 
            border-radius: 12px; 
            font-size: 12px; 
            font-weight: 800; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            cursor: pointer; 
            transition: 0.3s; 
            font-family: 'Montserrat', sans-serif;
        }
        .btn-submit:hover { background: var(--gold-hover); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2); }

        /* --- SWEETALERT CUSTOM --- */
        .premium-swal-popup { background: var(--bg-surface) !important; border: 1px solid var(--border-color) !important; border-radius: 16px !important; color: var(--text-main) !important; }
        .ecommerce-toast { border-radius: 12px !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }
    </style>
</head>
<body>

    <!-- SCRIPT PENGINGAT TEMA -->
    <script>
        if (localStorage.getItem('erna_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>
    <!-- ======================= -->

    <div class="saldo-container">
        <div class="saldo-card">
            <div class="card-header">
                <a href="{{ url('/profile') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
                <h2>Saldo ERNA Pay</h2>
            </div>

            <div class="balance-box">
                <span class="balance-label"><i class="fas fa-wallet" style="margin-right: 5px;"></i> Total Saldo Anda</span>
                <div class="balance-amount">Rp {{ number_format(Auth::user()->saldo ?? 0, 0, ',', '.') }}</div>
            </div>

            <form action="{{ url('/saldo-erna-pay') }}" method="POST" id="topUpForm">
                @csrf
                
                <div class="form-group">
                    <label>Nominal Top Up</label>
                    <div class="input-wrapper">
                        <span>Rp</span>
                        <input type="text" name="nominal" id="nominalInput" class="form-control" placeholder="Contoh: 50000" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                </div>

                <div class="form-group">
                    <label>Pilih Metode Pembayaran</label>
                    <div class="payment-methods">
                        
                        <label class="payment-label">
                            <input type="radio" name="metode_pembayaran" value="Bank Transfer" checked required>
                            <div class="payment-card">
                                <i class="fas fa-university main-icon"></i>
                                <span>Bank Transfer</span>
                                <div class="check-icon"><i class="fas fa-check-circle"></i></div>
                            </div>
                        </label>

                        <label class="payment-label">
                            <input type="radio" name="metode_pembayaran" value="QRIS" required>
                            <div class="payment-card">
                                <i class="fas fa-qrcode main-icon"></i>
                                <span>QRIS</span>
                                <div class="check-icon"><i class="fas fa-check-circle"></i></div>
                            </div>
                        </label>

                    </div>
                </div>

                <button type="submit" class="btn-submit">Top Up Sekarang</button>
            </form>
        </div>
    </div>

    <script>
        function getSwalColors() {
            return {
                bg: document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a',
                text: document.body.classList.contains('light-mode') ? '#111111' : '#ffffff'
            };
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: 'var(--bg-surface)',
            color: 'var(--text-main)',
            customClass: {
                popup: 'ecommerce-toast'
            }
        });

        @if(session('error'))
            Toast.fire({ icon: 'error', title: "{{ session('error') }}", iconColor: '#e74c3c' });
        @endif

        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}", iconColor: '#D4AF37' });
        @endif

        document.getElementById('topUpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const nominal = document.getElementById('nominalInput').value;
            const metode = document.querySelector('input[name="metode_pembayaran"]:checked').value;
            let c = getSwalColors();

            if(parseInt(nominal) < 10000) {
                Toast.fire({
                    icon: 'error',
                    title: 'Minimal Top Up adalah Rp 10.000',
                    iconColor: '#e74c3c'
                });
                return;
            }

            Swal.fire({
                title: '<span style="color:#D4AF37; font-family:\'Playfair Display\'">Memproses Permintaan</span>',
                html: `Membuka gateway pembayaran <b>${metode}</b>...`,
                allowOutsideClick: false,
                background: c.bg,
                color: c.text,
                customClass: { popup: 'premium-swal-popup' },
                didOpen: () => { Swal.showLoading(); }
            });

            setTimeout(() => {
                form.submit();
            }, 1500);
        });
    </script>
</body>
</html>