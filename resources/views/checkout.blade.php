@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
    $user = Auth::user();

    // Kalkulasi Total
    $totalHarga = 0;
    foreach ($carts as $cart) {
        $totalHarga += ($cart->product->harga * $cart->jumlah);
    }

    $diskonVip = ($user->vip_paket == 'GOLD') ? $totalHarga * 0.05 : 0;
    $ongkir = ($user->vip_paket == 'GOLD' || $user->vip_paket == 'SILVER') ? 0 : 20000;
    
    $totalTagihan = $totalHarga - $diskonVip + $ongkir;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Pesanan | {{ $nama_toko }}</title>
    
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
            --success: #2ecc71;
            --input-bg: rgba(255, 255, 255, 0.02);
            --input-hover: rgba(255, 255, 255, 0.05);
            --danger: #e74c3c;
        }

        body.light-mode {
            --bg-dark: #f5f5f5;
            --bg-surface: #ffffff;
            --text-main: #111111;
            --text-muted: #666666;
            --border-color: #dddddd;
            --input-bg: rgba(0, 0, 0, 0.02);
            --input-hover: rgba(0, 0, 0, 0.05);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            padding: 40px 20px;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        body:not(.light-mode) { background: radial-gradient(circle at top center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at top center, #ffffff 0%, var(--bg-dark) 100%); }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            animation: eleganceIn 0.8s ease forwards;
        }

        @keyframes eleganceIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* --- HEADER --- */
        .header { display: flex; align-items: center; margin-bottom: 40px; position: relative; transition: 0.4s ease;}
        .btn-back {
            color: var(--text-muted); text-decoration: none; font-size: 13px; font-weight: 700;
            display: flex; align-items: center; gap: 10px; transition: 0.3s;
            text-transform: uppercase; letter-spacing: 1px; position: absolute; left: 0;
        }
        .btn-back:hover { color: var(--gold); transform: translateX(-5px); }
        .header h1 { font-family: 'Playfair Display', serif; font-size: 32px; color: var(--gold); margin: 0 auto; }

        /* --- LAYOUT --- */
        .checkout-layout { display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px; align-items: start; }

        /* --- CARDS --- */
        .checkout-card {
            background: var(--bg-surface); border: 1px solid var(--border-color);
            border-radius: 16px; padding: 30px; margin-bottom: 25px; transition: 0.4s ease;
        }
        
        body:not(.light-mode) .checkout-card { box-shadow: 0 20px 40px rgba(0,0,0,0.4); border-color: rgba(212, 175, 55, 0.15); }
        body.light-mode .checkout-card { box-shadow: 0 20px 40px rgba(0,0,0,0.05); }

        .card-title {
            font-size: 12px; font-weight: 700; color: var(--gold); text-transform: uppercase;
            letter-spacing: 2px; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;
            border-bottom: 1px solid var(--border-color); padding-bottom: 15px; transition: 0.4s ease;
        }
        .card-title i { font-size: 16px; }

        /* ALAMAT PENGIRIMAN */
        .address-box { line-height: 1.8; font-size: 14px; color: var(--text-main); transition: 0.4s ease; }
        .address-name { font-weight: 700; font-size: 16px; margin-bottom: 5px; color: var(--gold); }
        .address-phone { color: var(--text-muted); font-size: 13px; transition: 0.4s ease; }
        .btn-edit-address {
            display: inline-block; margin-top: 15px; color: var(--gold); text-decoration: none;
            font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s;
            border: 1px solid var(--gold); padding: 8px 20px; border-radius: 30px;
        }
        .btn-edit-address:hover { background: var(--gold); color: var(--bg-dark); }

        /* METODE PEMBAYARAN (RADIO CARDS) */
        .payment-group-title { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin: 20px 0 10px; font-weight: 600; transition: 0.4s ease;}
        
        .payment-label { cursor: pointer; display: block; margin-bottom: 15px; }
        .payment-label input { display: none; }
        
        .payment-option {
            background: var(--input-bg); border: 1px solid var(--border-color);
            border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 20px;
            transition: all 0.3s ease; position: relative;
        }
        .payment-icon { width: 40px; height: 40px; background: rgba(136, 136, 136, 0.05); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--text-muted); transition: 0.3s; border: 1px solid var(--border-color); }
        .payment-info h4 { font-size: 14px; color: var(--text-main); margin-bottom: 3px; transition: 0.4s ease; }
        .payment-info p { font-size: 11px; color: var(--text-muted); transition: 0.4s ease; }
        
        .check-circle { position: absolute; right: 20px; font-size: 18px; color: var(--gold); opacity: 0; transform: scale(0.5); transition: 0.3s; }

        .payment-label:hover .payment-option { border-color: rgba(212, 175, 55, 0.4); background: var(--input-hover); }
        .payment-label input:checked + .payment-option { border-color: var(--gold); background: rgba(212, 175, 55, 0.05); box-shadow: 0 5px 20px rgba(212, 175, 55, 0.1); }
        .payment-label input:checked + .payment-option .payment-icon { background: var(--gold); color: #111; border-color: var(--gold);}
        .payment-label input:checked + .payment-option .payment-info h4 { color: var(--gold); }
        .payment-label input:checked + .payment-option .check-circle { opacity: 1; transform: scale(1); }

        /* RINGKASAN PRODUK KIRI */
        .item-row-left { display: flex; justify-content: space-between; font-size: 13px; color: var(--text-muted); margin-bottom: 12px; transition: 0.4s ease;}
        .item-row-left strong { color: var(--text-main); font-weight: 600; transition: 0.4s ease;}

        /* CATATAN */
        .note-input {
            width: 100%; padding: 15px; border: 1px solid var(--border-color); border-radius: 8px; 
            background: transparent; color: var(--text-main); font-family: inherit; font-size: 13px; 
            resize: vertical; box-sizing: border-box; outline: none; transition: 0.4s ease;
        }
        .note-input:focus { border-color: var(--gold); box-shadow: 0 0 10px rgba(212, 175, 55, 0.1); }
        .note-input::placeholder { color: var(--text-muted); }

        /* --- SISI KANAN (SUMMARY) --- */
        .summary-wrapper { position: sticky; top: 20px; }

        /* PROMO BOX */
        .promo-input-group { display: flex; gap: 10px; margin-bottom: 30px; }
        .promo-input-group input {
            flex: 1; padding: 15px; background: transparent; border: 1px solid var(--border-color);
            border-radius: 8px; color: var(--text-main); font-size: 12px; font-family: 'Montserrat'; outline: none; transition: 0.4s ease; text-transform: uppercase;
        }
        .promo-input-group input:focus { border-color: var(--gold); }
        .promo-input-group input::placeholder { color: var(--text-muted); }
        
        .btn-apply-promo {
            width: auto; padding: 10px 20px; background: var(--gold); color: #111; border: none;
            border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase; cursor: pointer; transition: 0.3s;
        }
        .btn-apply-promo:hover { background: var(--gold-hover); }
        
        /* SUMMARY LIST */
        .summary-row { display: flex; justify-content: space-between; font-size: 13px; color: var(--text-muted); margin-bottom: 15px; transition: 0.4s ease;}
        .summary-row.discount { color: var(--success); font-weight: 600; }
        .summary-total {
            display: flex; justify-content: space-between; align-items: center; margin-top: 20px;
            padding-top: 20px; border-top: 1px dashed var(--border-color); transition: 0.4s ease;
        }
        .summary-total .label { font-size: 13px; font-weight: 700; text-transform: uppercase; color: var(--text-main); transition: 0.4s ease;}
        .summary-total .amount { font-family: 'Playfair Display', serif; font-size: 26px; font-weight: 700; color: var(--gold); }

        .btn-submit {
            width: 100%; padding: 18px; background: var(--gold); color: #111; border: none;
            border-radius: 12px; font-size: 12px; font-weight: 800; text-transform: uppercase;
            letter-spacing: 2px; cursor: pointer; transition: 0.3s; margin-top: 30px; font-family: 'Montserrat'; display: inline-block; text-align: center;
        }
        .btn-submit:hover { background: var(--gold-hover); transform: translateY(-3px); box-shadow: 0 10px 25px rgba(212, 175, 55, 0.3); }
        
        .terms-text { font-size: 10px; color: var(--text-muted); text-align: center; margin-top: 15px; line-height: 1.6; transition: 0.4s ease;}

        @media (max-width: 768px) {
            .checkout-layout { grid-template-columns: 1fr; }
            .header { justify-content: center; flex-direction: column; align-items: flex-start; }
            .btn-back { position: relative; left: auto; margin-bottom: 15px; }
            .header h1 { font-size: 24px; margin: 0; }
        }

        /* --- THEME CUSTOM SWEETALERT LENGKAP --- */
        .ecommerce-toast { border-radius: 12px !important; padding: 10px 20px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.8) !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }
        body.light-mode .ecommerce-toast { box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
        
        .premium-swal-popup { background: var(--bg-surface) !important; border: 1px solid var(--border-color) !important; border-radius: 16px !important; color: var(--text-main) !important; box-shadow: 0 20px 50px rgba(0,0,0,0.7) !important;}
        body.light-mode .premium-swal-popup { box-shadow: 0 20px 50px rgba(0,0,0,0.1) !important; }
        
        .premium-swal-title { color: var(--gold) !important; font-family: 'Playfair Display', serif !important; }
        
        /* QRIS Image Styling Dinamis */
        .qris-image { width: 220px; border-radius: 10px; border: 1px solid var(--border-color); padding: 10px; margin-top: 15px; background: #fff; transition: 0.4s ease;}
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

    <div class="container">
        
        <div class="header">
            <a href="{{ url('/keranjang') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1>Checkout Pesanan</h1>
        </div>

        <form action="{{ url('/checkout/proses') }}" method="POST" id="checkoutForm">
            @csrf
            
            <div class="checkout-layout">
                
                <div class="checkout-left">
                    
                    <div class="checkout-card">
                        <div class="card-title"><i class="fas fa-map-marker-alt"></i> Alamat Pengiriman</div>
                        <div class="address-box">
                            <div class="address-name">{{ $user->name }} <span class="address-phone">({{ $user->no_hp ?? 'Belum ada No HP' }})</span></div>
                            <div>{{ $user->alamat ?? 'Alamat belum diisi. Silakan lengkapi profil Anda.' }}</div>
                            <div>No. Rumah: {{ $user->no_rumah ?? '-' }} | Kode Pos: {{ $user->kode_pos ?? '-' }}</div>
                        </div>
                        <a href="{{ url('/profile') }}" class="btn-edit-address">Ubah Alamat</a>
                    </div>

                    <div class="checkout-card">
                        <div class="card-title"><i class="fas fa-credit-card"></i> Pilih Metode Pembayaran</div>
                        
                        <label class="payment-label">
                            <input type="radio" name="metode_pembayaran" value="ERNA Pay" checked required>
                            <div class="payment-option">
                                <div class="payment-icon"><i class="fas fa-wallet"></i></div>
                                <div class="payment-info">
                                    <h4>ERNA Pay</h4>
                                    <p>Saldo Aktif: Rp {{ number_format($user->saldo ?? 0, 0, ',', '.') }}</p>
                                </div>
                                <i class="fas fa-check-circle check-circle"></i>
                            </div>
                        </label>

                        <div class="payment-group-title">Bayar Online via Midtrans</div>
                        <label class="payment-label">
                            <input type="radio" name="metode_pembayaran" value="Midtrans">
                            <div class="payment-option">
                                <div class="payment-icon"><i class="fas fa-credit-card"></i></div>
                                <div class="payment-info">
                                    <h4>Midtrans (Semua Metode)</h4>
                                    <p>Kartu Kredit/Debit, Transfer Bank, GoPay, OVO, Dana, ShopeePay, QRIS & lebih banyak lagi</p>
                                </div>
                                <i class="fas fa-check-circle check-circle"></i>
                            </div>
                        </label>

                    </div>

                    <div class="checkout-card">
                        <div class="card-title"><i class="fas fa-box"></i> Ringkasan Produk</div>
                        @foreach($carts as $cart)
                            <div class="item-row-left" style="align-items: flex-start; flex-direction: column; margin-bottom: 15px;">
                                <div style="display: flex; justify-content: space-between; width: 100%;">
                                    <span>{{ $cart->jumlah }}x {{ $cart->product->nama_produk }}</span>
                                    <strong>Rp {{ number_format($cart->product->harga * $cart->jumlah, 0, ',', '.') }}</strong>
                                </div>
                                @if($cart->variant)
                                    <div style="font-size: 11px; margin-top: 4px; color: var(--text-muted);">
                                        Varian: {{ $cart->variant->warna }} {{ $cart->variant->ukuran ? ' - ' . $cart->variant->ukuran : '' }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="checkout-card">
                        <div class="card-title"><i class="fas fa-comment-dots"></i> Catatan untuk Penjual (Opsional)</div>
                        <textarea name="catatan" rows="3" class="note-input" placeholder="Tinggalkan pesan khusus untuk pesanan ini... (Misal: Tolong packing pakai kardus ganda ya)"></textarea>
                    </div>

                </div>

                <div class="summary-wrapper">
                    <div class="checkout-card" style="margin-bottom: 0;">
                        
                        <div class="card-title"><i class="fas fa-ticket-alt"></i> Makin Hemat Pakai Promo</div>
                        <div class="promo-input-group">
                            <input type="text" name="kode_voucher_input" id="kode_voucher_input" placeholder="Masukkan Kode Voucher">
                            <button type="button" class="btn-apply-promo" onclick="cekVoucher()">Terapkan</button>
                        </div>

                        <div class="card-title" style="margin-top: 40px;"><i class="fas fa-file-invoice-dollar"></i> Rincian Pembayaran</div>
                        
                        <div class="summary-row">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($totalHarga, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Biaya Pengiriman</span>
                            <span>
                                @if($ongkir == 0)
                                    <span style="color: var(--success); font-weight: 600;">GRATIS</span>
                                @else
                                    Rp {{ number_format($ongkir, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>

                        @if($diskonVip > 0)
                        <div class="summary-row discount">
                            <span>Diskon VIP (5%)</span>
                            <span>-Rp {{ number_format($diskonVip, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <div class="summary-total">
                            <span class="label">Total Tagihan</span>
                            <span class="amount">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                        </div>

                        <button type="button" class="btn-submit" id="btn-proses-bayar">Proses Pembayaran</button>
                        <p class="terms-text">Dengan mengeklik tombol, Anda menyetujui syarat dan ketentuan transaksi di ERNA Thrifting.</p>
                    </div>
                </div>

            </div>
        </form>

    </div>

    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        // Set warna dinamis untuk SweetAlert
        function getSwalColors() {
            return {
                bg: document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a',
                text: document.body.classList.contains('light-mode') ? '#111111' : '#ffffff',
                border: document.body.classList.contains('light-mode') ? '#dddddd' : '#333333'
            };
        }

        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
            background: 'var(--bg-surface)', color: 'var(--text-main)', customClass: { popup: 'ecommerce-toast' }
        });

        // Tampilkan error dari backend jika ada
        @if(session('error'))
            let cErr = getSwalColors();
            Swal.fire({
                title: '<span class="premium-swal-title">Transaksi Gagal</span>',
                text: "{{ session('error') }}", icon: 'error', confirmButtonColor: '#D4AF37',
                background: cErr.bg, color: cErr.text, customClass: { popup: 'premium-swal-popup' }
            });
        @endif

        function cekVoucher() {
            const kode = document.getElementById('kode_voucher_input').value.trim();
            let cVoucher = getSwalColors();
            
            if(kode === '') {
                Swal.fire({ 
                    icon: 'warning', 
                    title: '<span class="premium-swal-title">Kosong!</span>', 
                    text: 'Silakan ketik kode voucher terlebih dahulu.', 
                    confirmButtonColor: '#D4AF37',
                    background: cVoucher.bg, color: cVoucher.text,
                    customClass: { popup: 'premium-swal-popup' }
                });
                return;
            }
            Swal.fire({ 
                icon: 'success', 
                title: '<span class="premium-swal-title">Voucher Diterima!</span>', 
                text: 'Kode siap digunakan. Jika kode valid, diskon akan otomatis memotong total belanja Anda saat mengeklik "Proses Pembayaran".', 
                confirmButtonColor: '#D4AF37', 
                background: cVoucher.bg, color: cVoucher.text, iconColor: '#D4AF37',
                customClass: { popup: 'premium-swal-popup' } 
            });
        }

        // Logic Proses Pembayaran
        document.getElementById('btn-proses-bayar').addEventListener('click', function() {
            const formAsli = document.getElementById('checkoutForm');
            const metodeTerpilih = document.querySelector('input[name="metode_pembayaran"]:checked').value;
            let cProses = getSwalColors();
            
            const userBalance = {{ Auth::user()->saldo ?? 0 }};
            const totalBill = {{ $totalTagihan }};

            // Jika memilih ERNA Pay
            if (metodeTerpilih === 'ERNA Pay') {
                if(userBalance < totalBill) {
                    Swal.fire({
                        icon: 'warning', 
                        title: '<span class="premium-swal-title">Saldo Tidak Cukup</span>', 
                        text: 'Saldo ERNA Pay Anda tidak mencukupi.',
                        showCancelButton: true, confirmButtonText: 'Top Up Sekarang', cancelButtonText: 'Pilih Metode Lain', 
                        confirmButtonColor: '#D4AF37', cancelButtonColor: cProses.border,
                        background: cProses.bg, color: cProses.text, iconColor: '#D4AF37',
                        customClass: { popup: 'premium-swal-popup' }
                    }).then((result) => { 
                        if (result.isConfirmed) { window.location.href = "{{ url('/saldo-erna-pay') }}"; } 
                    });
                } else {
                    Swal.fire({
                        title: '<span class="premium-swal-title">Memproses Pesanan</span>',
                        html: `Menarik saldo dari <b>ERNA Pay</b>...`,
                        allowOutsideClick: false, background: cProses.bg, color: cProses.text, customClass: { popup: 'premium-swal-popup' },
                        didOpen: () => { Swal.showLoading(); }
                    });
                    setTimeout(() => { formAsli.submit(); }, 1500);
                }
            } 
            // Jika memilih Midtrans — kirim form dulu untuk buat order & dapat snap_token
            else if (metodeTerpilih === 'Midtrans') {
                // Tampilkan loading sementara kirim ke server
                Swal.fire({
                    title: '<span class="premium-swal-title">Menyiapkan Pembayaran</span>',
                    html: 'Menghubungi Midtrans...',
                    allowOutsideClick: false,
                    background: cProses.bg, color: cProses.text,
                    customClass: { popup: 'premium-swal-popup' },
                    didOpen: () => { Swal.showLoading(); }
                });

                // Kirim form via AJAX untuk mendapat snap token
                const formData = new FormData(formAsli);
                fetch("{{ url('/checkout/midtrans-token') }}", {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.snap_token && data.order_id) {
                        Swal.close();
                        // Buka popup Midtrans Snap
                        snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                // Langsung update status ke Dikemas via AJAX
                                fetch("{{ url('/checkout/payment-success') }}/" + data.order_id, {
                                    method: 'POST',
                                    credentials: 'same-origin',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({ payment_type: result.payment_type })
                                }).finally(() => {
                                    window.location.href = "{{ url('/checkout/success') }}/" + data.order_id;
                                });
                            },
                            onPending: function(result) {
                                window.location.href = "{{ url('/checkout/success') }}/" + data.order_id;
                            },
                            onError: function(result) {
                                Swal.fire({
                                    icon: 'error',
                                    title: '<span class="premium-swal-title">Pembayaran Gagal</span>',
                                    text: 'Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.',
                                    confirmButtonColor: '#D4AF37',
                                    background: cProses.bg, color: cProses.text,
                                    customClass: { popup: 'premium-swal-popup' }
                                });
                            },
                            onClose: function() {
                                // User menutup popup — arahkan ke halaman pesanan agar bisa bayar nanti
                                window.location.href = "{{ url('/checkout/success') }}/" + data.order_id;
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '<span class="premium-swal-title">Gagal</span>',
                            text: data.message || 'Gagal membuat transaksi. Silakan coba lagi.',
                            confirmButtonColor: '#D4AF37',
                            background: cProses.bg, color: cProses.text,
                            customClass: { popup: 'premium-swal-popup' }
                        });
                    }
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: '<span class="premium-swal-title">Koneksi Bermasalah</span>',
                        text: 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.',
                        confirmButtonColor: '#D4AF37',
                        background: cProses.bg, color: cProses.text,
                        customClass: { popup: 'premium-swal-popup' }
                    });
                });
            }
        });
    </script>
</body>
</html>