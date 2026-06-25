@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';

    // [LOGIKA TOMBOL KEMBALI CERDAS]
    $halamanSebelumnya = url()->previous();
    
    if (strpos($halamanSebelumnya, 'riwayat-pesanan') !== false || strpos($halamanSebelumnya, 'checkout') !== false || strpos($halamanSebelumnya, 'pesanan/lacak') !== false) {
        $linkKembali = url('/');
    } else {
        $linkKembali = $halamanSebelumnya;
    }
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Riwayat Pesanan' }} | {{ $nama_toko }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
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
            --danger: #e74c3c;
            --success: #2ecc71; 
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
            padding: 100px 20px 40px;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        body:not(.light-mode) { background: radial-gradient(circle at top center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at top center, #ffffff 0%, var(--bg-dark) 100%); }

        .container { max-width: 900px; margin: 0 auto; animation: eleganceIn 0.8s ease forwards; }

        @keyframes eleganceIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* --- HEADER --- */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; position: relative; }
        .btn-back { color: var(--text-muted); text-decoration: none; font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px; z-index: 5; }
        .btn-back i { font-size: 12px; }
        .btn-back:hover { color: var(--gold); transform: translateX(-3px); }
        .header h1 { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--gold); margin: 0; position: absolute; left: 50%; transform: translateX(-50%); width: 100%; text-align: center; pointer-events: none; }

        /* --- TABS NAVIGASI (REFINED PILLS) --- */
        .order-tabs { 
            display: flex; gap: 12px; margin-bottom: 35px; overflow-x: auto; 
            padding: 5px 0 15px; scrollbar-width: none; -ms-overflow-style: none;
            -webkit-overflow-scrolling: touch;
        }
        .order-tabs::-webkit-scrollbar { display: none; }
        
        .tab-item { 
            padding: 14px 28px; background: rgba(255, 255, 255, 0.03); 
            border: 1px solid rgba(255, 255, 255, 0.08); color: var(--text-muted); 
            border-radius: 40px; font-size: 10.5px; font-weight: 700; 
            text-transform: uppercase; letter-spacing: 1.5px; white-space: nowrap; 
            cursor: pointer; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); 
            text-decoration: none; 
        }
        
        body.light-mode .tab-item { background: rgba(0, 0, 0, 0.03); border-color: rgba(0, 0, 0, 0.08); }
        
        .tab-item:hover { background: rgba(212, 175, 55, 0.1); color: var(--gold); border-color: rgba(212, 175, 55, 0.3); transform: translateY(-2px); }
        .tab-item.active { background: var(--gold); color: #000; border-color: var(--gold); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }

        /* --- KARTU PESANAN --- */
        .order-card { background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; padding: 30px; margin-bottom: 25px; transition: 0.4s ease; }
        
        body:not(.light-mode) .order-card { box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        body.light-mode .order-card { box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        body:not(.light-mode) .order-card:hover { border-color: rgba(212, 175, 55, 0.4); box-shadow: 0 15px 40px rgba(0,0,0,0.5); transform: translateY(-3px); }
        body.light-mode .order-card:hover { border-color: rgba(212, 175, 55, 0.4); box-shadow: 0 15px 40px rgba(0,0,0,0.1); transform: translateY(-3px); }

        .order-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 20px; border-bottom: 1px solid var(--border-color); margin-bottom: 20px; flex-wrap: wrap; gap: 15px; transition: 0.4s ease;}
        .order-meta { display: flex; align-items: center; gap: 15px; font-size: 11px; color: var(--text-muted); flex-wrap: wrap; }
        .order-meta i { color: var(--gold); font-size: 14px; }
        .invoice-number { font-weight: 700; color: var(--gold); letter-spacing: 1px; }

        .status-badge { padding: 6px 15px; border-radius: 6px; font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; border: 1px solid transparent; }
        
        /* Status Badges */
        .status-menunggu { background: rgba(243, 156, 18, 0.1); color: #f39c12; border-color: rgba(243, 156, 18, 0.2); }
        .status-dikemas { background: rgba(230, 126, 34, 0.1); color: #e67e22; border-color: rgba(230, 126, 34, 0.2); }
        .status-dikirim { background: rgba(52, 152, 219, 0.1); color: #3498db; border-color: rgba(52, 152, 219, 0.2); }
        .status-selesai { background: rgba(39, 174, 96, 0.1); color: #2ecc71; border-color: rgba(39, 174, 96, 0.2); }
        .status-dibatalkan { background: rgba(231, 76, 60, 0.1); color: #e74c3c; border-color: rgba(231, 76, 60, 0.2); }

        .product-item { display: flex; gap: 20px; margin-bottom: 25px; align-items: center; }
        .product-item:last-child { margin-bottom: 0; }
        .product-img { width: 80px; height: 80px; border-radius: 12px; object-fit: cover; background: var(--border-color); border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; font-size: 24px; color: var(--text-muted); transition: 0.4s ease;}
        
        .product-detail { flex: 1; }
        .product-detail h3 { font-size: 15px; margin-bottom: 8px; color: var(--text-main); font-weight: 600; transition: 0.4s ease;}
        .product-detail p { font-size: 12px; color: var(--text-muted); transition: 0.4s ease;}
        .price-tag { font-family: 'Montserrat', sans-serif; font-size: 15px; color: var(--text-main); font-weight: 600; text-align: right; transition: 0.4s ease;}

        .order-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid var(--border-color); margin-top: 20px; flex-wrap: wrap; gap: 20px; transition: 0.4s ease;}
        .total-pay { font-size: 11px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; transition: 0.4s ease;}
        .total-pay span { font-family: 'Playfair Display', serif; font-size: 26px; color: var(--gold); font-weight: 700; margin-left: 15px; text-transform: none; letter-spacing: 0; }

        .action-btns { display: flex; gap: 12px; flex-wrap: wrap; }
        .btn-action { padding: 12px 25px; border-radius: 8px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; cursor: pointer; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 6px; }
        
        .btn-outline { border: 1px solid var(--border-color); color: var(--text-main); background: transparent; }
        body:not(.light-mode) .btn-outline:hover { border-color: var(--gold); color: var(--gold); background: rgba(212, 175, 55, 0.05); }
        body.light-mode .btn-outline:hover { border-color: var(--gold); color: var(--gold); background: rgba(212, 175, 55, 0.1); }
        
        .btn-danger-outline { border: 1px solid rgba(231, 76, 60, 0.3); color: var(--danger); background: transparent; }
        .btn-danger-outline:hover { border-color: var(--danger); color: #fff; background: var(--danger); box-shadow: 0 5px 15px rgba(231, 76, 60, 0.2); }
        
        .btn-gold { background: var(--gold); color: #111; border: 1px solid var(--gold); }
        .btn-gold:hover { background: var(--gold-hover); border-color: var(--gold-hover); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(212, 175, 55, 0.3); }

        .btn-terima { background: var(--success); color: #fff; border: 1px solid var(--success); }
        .btn-terima:hover { background: #27ae60; border-color: #27ae60; color: #fff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3); }

        .empty-state { text-align: center; padding: 80px 20px; background: var(--bg-surface); border-radius: 16px; border: 1px dashed var(--border-color); transition: 0.4s ease;}
        body:not(.light-mode) .empty-state { box-shadow: inset 0 0 20px rgba(0,0,0,0.5); }
        
        .empty-state i { font-size: 60px; color: var(--gold); opacity: 0.5; margin-bottom: 20px; }
        .empty-state h3 { font-family: 'Playfair Display', serif; color: var(--gold); font-size: 24px; margin-bottom: 10px; }
        .empty-state p { font-size: 13px; color: var(--text-muted); margin-bottom: 30px; }

        /* --- MODAL ULASAN --- */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(5px); }
        .modal-content { background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; width: 90%; max-width: 400px; box-shadow: 0 20px 50px rgba(0,0,0,0.8); overflow: hidden; animation: eleganceIn 0.4s ease; transition: 0.4s ease;}
        
        .modal-header { padding: 20px 25px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; transition: 0.4s ease;}
        .modal-header h3 { color: var(--gold); font-family: 'Playfair Display', serif; font-size: 20px; }
        .btn-close-modal { background: none; border: none; color: var(--text-muted); font-size: 20px; cursor: pointer; transition: 0.3s; }
        .btn-close-modal:hover { color: var(--danger); transform: rotate(90deg); }
        .modal-body { padding: 25px; text-align: center; }
        
        .review-product-name { font-size: 14px; color: var(--text-main); margin-bottom: 20px; font-weight: 600; line-height: 1.4; transition: 0.4s ease;}
        .star-rating { display: flex; justify-content: center; gap: 10px; margin-bottom: 25px; }
        .star-rating i { font-size: 30px; color: var(--border-color); cursor: pointer; transition: 0.2s; }
        .star-rating i.active { color: var(--gold); text-shadow: 0 0 10px rgba(212, 175, 55, 0.4); }
        
        .input-control { width: 100%; padding: 15px; background: transparent; border: 1px solid var(--border-color); border-radius: 12px; color: var(--text-main); font-family: 'Montserrat', sans-serif; font-size: 13px; outline: none; transition: 0.3s; resize: vertical; margin-bottom: 20px; }
        .input-control:focus { border-color: var(--gold); box-shadow: 0 0 10px rgba(212, 175, 55, 0.1); }
        .w-100 { width: 100%; }

        /* --- THEME CUSTOM SWEETALERT PREMIUM (DIPERBARUI) --- */
        .ecommerce-toast { border-radius: 12px !important; padding: 10px 20px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.8) !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }
        body.light-mode .ecommerce-toast { box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
        
        .premium-swal-popup {
            border-radius: 20px !important;
            padding: 2em !important;
            border: 1px solid var(--border-color) !important;
        }

        .premium-swal-title {
            font-family: 'Playfair Display', serif !important;
            font-size: 28px !important;
            margin-bottom: 10px !important;
        }

        .premium-swal-content {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 14px !important;
            color: var(--text-muted) !important;
        }

        .premium-swal-button-confirm {
            padding: 12px 30px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            border-radius: 10px !important;
        }

        .premium-swal-button-cancel {
            padding: 12px 30px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            border-radius: 10px !important;
            background: #888888 !important;
        }

        @media (max-width: 768px) {
            .header { margin-bottom: 30px; height: 40px; }
            .btn-back { position: relative; left: auto; transform: none !important; margin-bottom: 0; }
            .header h1 { font-size: 20px; width: auto; white-space: nowrap; }
            .order-card { padding: 20px; }
            .order-header { flex-direction: column; align-items: flex-start; gap: 15px; }
            .status-badge { align-self: flex-start; }
            .product-item { flex-direction: column; align-items: flex-start; gap: 15px; }
            .price-tag { text-align: left; }
            .order-footer { flex-direction: column; align-items: flex-start; gap: 10px; }
            
            .total-pay { display: block; width: 100%; font-size: 10px; margin-bottom: 5px; }
            .total-pay span { display: block; font-size: 22px; margin-left: 0; margin-top: 5px; }
            .mobile-break { display: block !important; }
            
            .action-btns { width: 100%; display: grid; grid-template-columns: 1fr; gap: 10px; }

            .ecommerce-toast {
                margin-bottom: 25px !important;
                border-radius: 50px !important;
                font-size: 12px !important;
                width: calc(100% - 40px) !important;
                border-color: var(--gold) !important;
                box-shadow: 0 8px 25px rgba(0,0,0,0.6) !important;
            }
        }

        @media (max-width: 480px) {
            .btn-back span { display: none; }
            .header h1 { font-size: 18px; }
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
    <!-- ======================= -->

    <div class="container">
        <div class="header">
            <a href="{{ $linkKembali }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> <span>Kembali</span>
            </a>
            <h1>Riwayat Pesanan</h1>
            @include('components.notif-bell')
        </div>

        <div class="order-tabs">
            <a href="?status=semua" class="tab-item {{ request('status', 'semua') == 'semua' ? 'active' : '' }}">Semua Pesanan</a>
            <a href="?status=dikemas" class="tab-item {{ request('status') == 'dikemas' ? 'active' : '' }}">Sedang Dikemas</a>
            <a href="?status=dikirim" class="tab-item {{ request('status') == 'dikirim' ? 'active' : '' }}">Dikirim</a>
            <a href="?status=selesai" class="tab-item {{ request('status') == 'selesai' ? 'active' : '' }}">Selesai</a>
            <a href="?status=dibatalkan" class="tab-item {{ request('status') == 'dibatalkan' ? 'active' : '' }}">Dibatalkan</a>
        </div>

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({ 
                        toast: true, 
                        position: window.innerWidth <= 576 ? 'bottom' : 'top-end', 
                        icon: 'success', 
                        title: "{{ session('success') }}", 
                        showConfirmButton: false, 
                        timer: 3000, 
                        background: 'var(--bg-surface)', 
                        color: 'var(--text-main)', 
                        iconColor: '#D4AF37', 
                        customClass: { popup: 'ecommerce-toast' } 
                    });
                });
            </script>
        @endif

        @php
            $currentStatus = request('status', 'semua');
            if($currentStatus == 'dikemas') { $filteredOrders = $orders->where('status', 'Dikemas'); } 
            elseif($currentStatus == 'dikirim') { $filteredOrders = $orders->where('status', 'Dikirim'); } 
            elseif($currentStatus == 'selesai') { $filteredOrders = $orders->where('status', 'Selesai'); } 
            elseif($currentStatus == 'dibatalkan') { $filteredOrders = $orders->where('status', 'Dibatalkan'); } 
            else { $filteredOrders = $orders; }
        @endphp

        @forelse($filteredOrders as $order)
            @php
                $badgeClass = strtolower(explode(' ', trim($order->status))[0]);
                $orderItems = \App\Models\OrderItem::with('product')->where('order_id', $order->id)->get();
                
                $firstItem = $orderItems->first();
                $firstProductId = $firstItem ? $firstItem->product_id : '';
                $firstProductName = $firstItem && $firstItem->product ? addslashes($firstItem->product->nama_produk) : 'Produk Thrift';
            @endphp
            
            <div class="order-card">
                <div class="order-header">
                    <div class="order-meta">
                        <i class="fas fa-shopping-bag"></i>
                        <span>{{ $order->created_at->format('d M Y | H:i') }} WIB</span>
                        <span style="color: var(--border-color);">|</span>
                        <span class="invoice-number">{{ $order->invoice }}</span>
                        @if($order->no_resi)
                            <span style="color: var(--border-color);">|</span>
                            <span style="font-size: 11px; font-weight: 600; color: #3498db;"><i class="fas fa-truck"></i> Resi: {{ $order->no_resi }}</span>
                        @endif
                    </div>
                    <div>
                        <span class="status-badge status-{{ $badgeClass }}">
                            <i class="fas fa-circle" style="font-size: 6px; margin-right: 6px; vertical-align: middle;"></i> {{ $order->status }}
                        </span>
                    </div>
                </div>

                <div class="order-body">
                    @foreach($orderItems as $item)
                        <div class="product-item">
                            @if($item->product && $item->product->gambar)
                                <img src="{{ asset($item->product->gambar) }}" class="product-img" alt="produk">
                            @else
                                <div class="product-img"><i class="fas fa-tshirt"></i></div>
                            @endif
                            
                            <div class="product-detail">
                                <h3>{{ $item->product->nama_produk ?? 'Produk Telah Dihapus Admin' }}</h3>
                                <p>{{ $item->jumlah }} barang x Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                            </div>
                            <div class="price-tag">Rp {{ number_format($item->jumlah * $item->harga_satuan, 0, ',', '.') }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="order-footer">
                    <div class="total-pay">
                        Total Belanja <br class="mobile-break" style="display:none;">
                        <span>Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</span>
                    </div>
                    <div class="action-btns">
                        <a href="{{ url('/pesanan/lacak/'.$order->id) }}" class="btn-action btn-outline">
                            <i class="fas fa-map-marker-alt"></i> Lacak Pesanan
                        </a>

                        {{-- JIKA STATUS SELESAI --}}
                        @if(strtolower($order->status) == 'selesai')
                            @if(!$order->status_retur)
                            <button class="btn-action btn-danger-outline" onclick="openReturModal('{{ $order->id }}', '{{ $order->invoice }}')">
                                <i class="fas fa-undo"></i> Ajukan Retur
                            </button>
                            @else
                            <button class="btn-action btn-outline" style="cursor: default;" disabled>
                                Status Retur: {{ $order->status_retur }}
                            </button>
                            @endif
                            
                            <a href="#" class="btn-action btn-outline" onclick="openReviewModal('{{ $order->invoice }}', '{{ $firstProductId }}', '{{ $firstProductName }}')">
                                <i class="far fa-star"></i> Beri Ulasan
                            </a>
                            
                            <a href="{{ url('/katalog/semua') }}" class="btn-action btn-gold">
                                <i class="fas fa-shopping-cart"></i> Beli Lagi
                            </a>
                            
                        {{-- JIKA STATUS DIKIRIM --}}
                        @elseif(strtolower($order->status) == 'dikirim')
                            <button type="button" class="btn-action btn-terima" onclick="konfirmasiDiterima({{ $order->id }})">
                                <i class="fas fa-box-open"></i> Pesanan Diterima
                            </button>

                            <form id="form-terima-{{ $order->id }}" action="{{ url('/pesanan/diterima/'.$order->id) }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        {{-- JIKA STATUS LAINNYa --}}
                        @else
                            <a href="#" class="btn-action btn-outline" onclick="Swal.fire({title: 'Fitur Cetak', text: 'Sistem Invoice sedang dikalibrasi.', icon: 'info', background: document.body.classList.contains('light-mode') ? '#fff' : '#1a1a1a', color: document.body.classList.contains('light-mode') ? '#111' : '#fff', confirmButtonColor: '#D4AF37', customClass: { popup: 'premium-swal-popup', title: 'premium-swal-title', htmlContainer: 'premium-swal-content', confirmButton: 'premium-swal-button-confirm' }})">
                                <i class="fas fa-print"></i> Cetak Invoice
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-receipt"></i>
                <h3>Belum Ada Pesanan</h3>
                <p>Anda belum memiliki riwayat transaksi di kategori ini.</p>
                <a href="{{ url('/') }}" class="btn-action btn-gold" style="padding: 15px 30px;">Mulai Belanja Sekarang</a>
            </div>
        @endforelse
    </div>

    <!-- Modal Retur -->
    <div id="returModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Pengajuan Retur</h3>
                <button class="btn-close-modal" onclick="closeReturModal()"><i class="fas fa-times"></i></button>
            </div>
            <form id="returForm" action="" method="POST" style="padding: 25px;">
                @csrf
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">Invoice</label>
                    <input type="text" id="returInvoice" disabled style="width: 100%; padding: 12px; background: transparent; border: 1px solid var(--border-color); color: var(--text-main); border-radius: 8px;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px;">Alasan Retur</label>
                    <textarea name="alasan_retur" id="returAlasan" rows="4" required placeholder="Jelaskan alasan pengembalian (contoh: barang cacat, tidak sesuai pesanan)..." class="input-control" style="width: 100%; box-sizing: border-box;"></textarea>
                </div>
                <div style="text-align: left; margin-bottom: 25px;">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); margin-bottom: 10px; text-transform: uppercase;">Unggah Bukti Kerusakan (Opsional)</label>
                    <div style="position: relative; width: 100%; height: 100px; border: 1px dashed var(--border-color); border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; overflow: hidden;" onclick="document.getElementById('returFoto').click()" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border-color)'">
                        <input type="file" id="returFoto" accept="image/*" style="display: none;" onchange="previewReturImage(this)">
                        <div id="returPhotoPlaceholder" style="text-align: center;">
                            <i class="fas fa-camera" style="font-size: 20px; color: var(--text-muted); margin-bottom: 5px;"></i>
                            <p style="font-size: 10px; color: var(--text-muted);">Klik untuk pilih foto</p>
                        </div>
                        <img id="returPhotoPreview" src="#" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>
                <button type="button" class="btn-action btn-gold w-100" style="width: 100%; border: none; font-family: 'Montserrat', sans-serif;" onclick="submitRetur()">Kirim Pengajuan</button>
            </form>
        </div>
    </div>

    <!-- Modal Ulasan -->
    <div id="reviewModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Beri Ulasan</h3>
                <button class="btn-close-modal" onclick="closeReviewModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <p id="reviewProductName" class="review-product-name">Nama Produk</p>
                
                <div class="star-rating" id="starContainer">
                    <i class="fas fa-star active" data-val="1"></i>
                    <i class="fas fa-star active" data-val="2"></i>
                    <i class="fas fa-star active" data-val="3"></i>
                    <i class="fas fa-star active" data-val="4"></i>
                    <i class="fas fa-star active" data-val="5"></i>
                </div>
                
                <input type="hidden" id="reviewRating" value="5">
                <input type="hidden" id="reviewInvoice" value="">
                <input type="hidden" id="reviewProductId" value="">
                
                <textarea id="reviewKomentar" class="input-control" placeholder="Ceritakan kepuasan Anda terhadap kondisi barang dan pelayanan kami..." rows="4"></textarea>
                
                <div style="text-align: left; margin-bottom: 25px;">
                    <label style="display: block; font-size: 11px; font-weight: 700; color: var(--text-muted); margin-bottom: 10px; text-transform: uppercase;">Unggah Foto Produk (Opsional)</label>
                    <div style="position: relative; width: 100%; height: 100px; border: 1px dashed var(--border-color); border-radius: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s; overflow: hidden;" onclick="document.getElementById('reviewFoto').click()" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border-color)'">
                        <input type="file" id="reviewFoto" accept="image/*" style="display: none;" onchange="previewReviewImage(this)">
                        <div id="photoPlaceholder" style="text-align: center;">
                            <i class="fas fa-camera" style="font-size: 20px; color: var(--text-muted); margin-bottom: 5px;"></i>
                            <p style="font-size: 10px; color: var(--text-muted);">Klik untuk pilih foto</p>
                        </div>
                        <img id="photoPreview" src="#" alt="Preview" style="display: none; width: 100%; height: 100%; object-fit: cover;">
                    </div>
                </div>

                <button class="btn-action btn-gold w-100" onclick="submitReview()">Kirim Ulasan</button>
            </div>
        </div>
    </div>

    <!-- Modal Crop -->
    <div id="cropModal" class="modal-overlay" style="z-index: 1100;">
        <div class="modal-content" style="max-width: 500px;">
            <div class="modal-header">
                <h3>Potong Foto</h3>
                <button class="btn-close-modal" onclick="closeCropModal()"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" style="padding: 0; background: #000; height: 350px;">
                <img id="imageToCrop" src="" style="max-width: 100%;">
            </div>
            <div class="modal-footer" style="padding: 15px; display: flex; gap: 10px; background: var(--bg-surface);">
                <button class="btn-action btn-outline" style="flex: 1;" onclick="closeCropModal()">Batal</button>
                <button class="btn-action btn-gold" style="flex: 1;" onclick="applyCrop()">Selesai Potong</button>
            </div>
        </div>
    </div>

    <script>
        let currentRating = 5;
        const stars = document.querySelectorAll('#starContainer i');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                currentRating = parseInt(this.getAttribute('data-val'));
                document.getElementById('reviewRating').value = currentRating;
                updateStarsVisual(currentRating);
            });
        });

        function updateStarsVisual(rating) {
            stars.forEach(star => {
                if(parseInt(star.getAttribute('data-val')) <= rating) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        function openReviewModal(invoice, productId, productName) {
            document.getElementById('reviewInvoice').value = invoice;
            document.getElementById('reviewProductId').value = productId;
            document.getElementById('reviewProductName').innerText = productName;
            document.getElementById('reviewKomentar').value = '';
            
            currentRating = 5;
            document.getElementById('reviewRating').value = 5;
            updateStarsVisual(5);

            document.getElementById('reviewModal').style.display = 'flex';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').style.display = 'none';
        }

        let cropper;
        let croppedBlob;
        let cropTarget = 'review'; // 'review' or 'retur'

        function previewReviewImage(input) {
            cropTarget = 'review';
            handleImageCrop(input);
        }

        function previewReturImage(input) {
            cropTarget = 'retur';
            handleImageCrop(input);
        }

        function handleImageCrop(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imageToCrop').src = e.target.result;
                    document.getElementById('cropModal').style.display = 'flex';
                    
                    if(cropper) cropper.destroy();
                    const image = document.getElementById('imageToCrop');
                    cropper = new Cropper(image, {
                        aspectRatio: cropTarget === 'review' ? 1 : 16/9, // Review is square, Retur can be widescreen
                        viewMode: 2,
                        guides: true,
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function closeCropModal() {
            document.getElementById('cropModal').style.display = 'none';
            if(cropper) cropper.destroy();
        }

        function applyCrop() {
            const canvas = cropper.getCroppedCanvas();
            canvas.toBlob((blob) => {
                croppedBlob = blob;
                if(cropTarget === 'review') {
                    const preview = document.getElementById('photoPreview');
                    const placeholder = document.getElementById('photoPlaceholder');
                    preview.src = canvas.toDataURL();
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                } else {
                    const preview = document.getElementById('returPhotoPreview');
                    const placeholder = document.getElementById('returPhotoPlaceholder');
                    preview.src = canvas.toDataURL();
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                }
                closeCropModal();
            }, 'image/jpeg');
        }

        let currentReturOrderId = null;
        function openReturModal(id, invoice) {
            currentReturOrderId = id;
            document.getElementById('returInvoice').value = invoice;
            document.getElementById('returAlasan').value = '';
            document.getElementById('returPhotoPreview').style.display = 'none';
            document.getElementById('returPhotoPlaceholder').style.display = 'block';
            croppedBlob = null;
            document.getElementById('returModal').style.display = 'flex';
        }

        function closeReturModal() {
            document.getElementById('returModal').style.display = 'none';
        }

        function submitRetur() {
            const alasan = document.getElementById('returAlasan').value;
            if(!alasan.trim()) {
                Swal.fire({toast: true, position: 'top-end', icon: 'error', title: 'Alasan retur tidak boleh kosong.', showConfirmButton: false, timer: 3000, background: 'var(--bg-surface)', color: 'var(--text-main)', customClass: { popup: 'ecommerce-toast' }});
                return;
            }

            let formData = new FormData();
            formData.append('alasan_retur', alasan);
            if(croppedBlob && cropTarget === 'retur') {
                formData.append('bukti_retur', croppedBlob, 'retur_proof.jpg');
            }

            Swal.fire({ title: 'Mengirim Pengajuan...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            fetch('{{ url("/pesanan/retur") }}/' + currentReturOrderId, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    window.location.reload();
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.' });
            });
        }



        function submitReview() {
            const invoice = document.getElementById('reviewInvoice').value;
            const productId = document.getElementById('reviewProductId').value;
            const rating = document.getElementById('reviewRating').value;
            const komentar = document.getElementById('reviewKomentar').value;

            if(!komentar.trim()) {
                Swal.fire({toast: true, position: 'top-end', icon: 'error', title: 'Kolom komentar tidak boleh kosong.', showConfirmButton: false, timer: 3000, background: 'var(--bg-surface)', color: 'var(--text-main)', customClass: { popup: 'ecommerce-toast' }});
                return;
            }

            let formData = new FormData();
            formData.append('invoice', invoice);
            formData.append('product_id', productId);
            formData.append('rating', rating);
            formData.append('komentar', komentar);
            if(croppedBlob) {
                formData.append('foto', croppedBlob, 'review_photo.jpg');
            }

            fetch('{{ url("/submit-ulasan") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    closeReviewModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Ulasan Terkirim!',
                        text: 'Terima kasih atas ulasan yang Anda berikan.',
                        background: document.body.classList.contains('light-mode') ? '#fff' : '#1a1a1a',
                        color: document.body.classList.contains('light-mode') ? '#111' : '#fff',
                        iconColor: '#D4AF37',
                        confirmButtonColor: '#D4AF37',
                        customClass: { 
                            popup: 'premium-swal-popup',
                            title: 'premium-swal-title',
                            htmlContainer: 'premium-swal-content',
                            confirmButton: 'premium-swal-button-confirm'
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({toast: true, position: 'top-end', icon: 'error', title: 'Terjadi kesalahan sistem.', showConfirmButton: false, timer: 3000, background: 'var(--bg-surface)', color: 'var(--text-main)', customClass: { popup: 'ecommerce-toast' }});
            });
        }

        // --- Fungsi Tombol Pesanan Diterima (TAMPILAN PREMIUM) ---
        function konfirmasiDiterima(orderId) {
            let swalBg = document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a';
            let swalColor = document.body.classList.contains('light-mode') ? '#111111' : '#ffffff';

            Swal.fire({
                title: 'Pesanan Diterima?',
                html: `Pastikan Anda telah menerima produk dalam kondisi baik sebelum menekan tombol ini. <br><br><span style="font-size:11px; color:var(--text-muted);">Sistem akan menyelesaikan pesanan ini.</span>`,
                icon: 'question',
                iconColor: '#2ecc71',
                showCancelButton: true,
                confirmButtonColor: '#2ecc71',
                cancelButtonColor: '#888888',
                confirmButtonText: 'Ya, Saya Terima',
                cancelButtonText: 'Batal',
                background: swalBg,
                color: swalColor,
                customClass: {
                    popup: 'premium-swal-popup',
                    title: 'premium-swal-title',
                    htmlContainer: 'premium-swal-content',
                    confirmButton: 'premium-swal-button-confirm',
                    cancelButton: 'premium-swal-button-cancel'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ 
                        title: 'Memproses...', 
                        allowOutsideClick: false, 
                        background: swalBg, 
                        color: swalColor,
                        customClass: {
                            popup: 'premium-swal-popup',
                            title: 'premium-swal-title'
                        },
                        didOpen: () => { Swal.showLoading(); } 
                    });
                    document.getElementById('form-terima-' + orderId).submit();
                }
            });
        }
    </script>
    <x-footer />
</body>
</html>