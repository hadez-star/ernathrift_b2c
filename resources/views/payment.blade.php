<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - ERNA THRIFTING</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
    <style>
        :root {
            --bg-color: #FAFAFA;
            --text-main: #2C2623;
            --text-muted: #777;
            --gold: #B08968;
            --gold-hover: #8c6b5d;
            --card-bg: #FFFFFF;
        }

        body.light-mode {
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        body.dark-mode {
            --bg-color: #121212;
            --text-main: #E0E0E0;
            --text-muted: #888;
            --card-bg: #1E1E1E;
            background-color: var(--bg-color);
            color: var(--text-main);
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .payment-container {
            background: var(--card-bg);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            color: var(--gold);
            margin-bottom: 10px;
        }

        p {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
        }

        .invoice-box {
            background: rgba(176, 137, 104, 0.05);
            border: 1px dashed var(--gold);
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .invoice-box strong {
            display: block;
            font-size: 18px;
            color: var(--text-main);
            margin-top: 5px;
        }

        .loader {
            border: 4px solid rgba(176, 137, 104, 0.2);
            border-top: 4px solid var(--gold);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn-retry {
            background: var(--gold);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }

        .btn-retry:hover {
            background: var(--gold-hover);
        }

        .btn-cancel {
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--text-muted);
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
            margin-left: 10px;
        }

        .btn-cancel:hover {
            background: rgba(0,0,0,0.05);
        }
    </style>
</head>
<body class="{{ request()->cookie('theme', 'light-mode') }}">

    <div class="payment-container">
        <h2>Selesaikan Pembayaran</h2>
        <p>Silakan selesaikan pembayaran Anda melalui jendela aman Midtrans.</p>
        
        <div class="invoice-box">
            <span>Nomor Invoice:</span>
            <strong>{{ $order->invoice }}</strong>
            <br>
            <span>Total Tagihan:</span>
            <strong>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</strong>
        </div>

        <div id="loader" class="loader"></div>
        <p id="loading-text">Membuka jendela pembayaran...</p>
        
        <div id="action-buttons" style="display: none;">
            <p>Jendela pembayaran tertutup? Klik tombol di bawah ini untuk membukanya kembali.</p>
            <button onclick="payWithMidtrans()" class="btn-retry">Bayar Sekarang</button>
            <a href="{{ url('/riwayat-pesanan') }}" class="btn-cancel">Batal / Nanti Saja</a>
        </div>
    </div>

    <script>
        const ORDER_ID    = {{ $order->id }};
        const STATUS_URL  = "{{ url('/payment/' . $order->id . '/status') }}";
        const RIWAYAT_URL = "{{ url('/riwayat-pesanan') }}";
        let pollingInterval = null;

        function getSwalColors() {
            let isDark = document.body.classList.contains('dark-mode');
            return {
                bg:   isDark ? '#1E1E1E' : '#FFFFFF',
                text: isDark ? '#E0E0E0' : '#2C2623'
            };
        }

        // Polling setiap 3 detik untuk mengecek apakah status sudah berubah di DB
        function startPolling() {
            pollingInterval = setInterval(async function() {
                try {
                    const res  = await fetch(STATUS_URL, { headers: { 'Accept': 'application/json' } });
                    const data = await res.json();

                    if (data.is_paid) {
                        stopPolling();
                        let c = getSwalColors();
                        Swal.fire({
                            icon: 'success', title: 'Pembayaran Berhasil!',
                            text: 'Pesanan Anda sedang diproses oleh admin.',
                            confirmButtonColor: '#B08968', background: c.bg, color: c.text
                        }).then(() => { window.location.href = RIWAYAT_URL; });
                    } else if (data.is_cancelled) {
                        stopPolling();
                        let c = getSwalColors();
                        Swal.fire({
                            icon: 'error', title: 'Pembayaran Dibatalkan / Expired',
                            text: 'Pesanan Anda dibatalkan karena pembayaran tidak selesai.',
                            confirmButtonColor: '#B08968', background: c.bg, color: c.text
                        }).then(() => { window.location.href = RIWAYAT_URL; });
                    }
                } catch(e) {
                    // Abaikan error jaringan sementara
                }
            }, 3000);
        }

        function stopPolling() {
            if (pollingInterval) {
                clearInterval(pollingInterval);
                pollingInterval = null;
            }
        }

        function payWithMidtrans() {
            document.getElementById('loader').style.display = 'block';
            document.getElementById('loading-text').style.display = 'block';
            document.getElementById('action-buttons').style.display = 'none';

            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    stopPolling();
                    let c = getSwalColors();
                    Swal.fire({
                        icon: 'success', title: 'Pembayaran Berhasil!',
                        text: 'Terima kasih, pembayaran Anda telah diterima.',
                        confirmButtonColor: '#B08968', background: c.bg, color: c.text
                    }).then(() => { window.location.href = RIWAYAT_URL; });
                },
                onPending: function(result){
                    // Tetap polling untuk menunggu konfirmasi dari Midtrans
                    let c = getSwalColors();
                    Swal.fire({
                        icon: 'info', title: 'Menunggu Konfirmasi Pembayaran',
                        text: 'Kami sedang memverifikasi pembayaran Anda. Halaman ini akan otomatis berubah ketika pembayaran dikonfirmasi.',
                        confirmButtonColor: '#B08968', background: c.bg, color: c.text
                    }).then(() => {
                        document.getElementById('loader').style.display = 'block';
                        document.getElementById('loading-text').textContent = 'Menunggu konfirmasi pembayaran...';
                        document.getElementById('loading-text').style.display = 'block';
                        document.getElementById('action-buttons').style.display = 'none';
                    });
                },
                onError: function(result){
                    stopPolling();
                    let c = getSwalColors();
                    Swal.fire({
                        icon: 'error', title: 'Pembayaran Gagal',
                        text: 'Terjadi kesalahan saat memproses pembayaran.',
                        confirmButtonColor: '#B08968', background: c.bg, color: c.text
                    }).then(() => {
                        document.getElementById('loader').style.display = 'none';
                        document.getElementById('loading-text').style.display = 'none';
                        document.getElementById('action-buttons').style.display = 'block';
                    });
                },
                onClose: function(){
                    document.getElementById('loader').style.display = 'none';
                    document.getElementById('loading-text').style.display = 'none';
                    document.getElementById('action-buttons').style.display = 'block';
                }
            });
        }

        // Buka popup dan mulai polling saat halaman dimuat
        document.addEventListener("DOMContentLoaded", function() {
            startPolling();
            setTimeout(function() { payWithMidtrans(); }, 1000);
        });
    </script>
</body>
</html>
