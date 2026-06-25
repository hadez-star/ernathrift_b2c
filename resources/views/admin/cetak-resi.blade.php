<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Resi Pengiriman - {{ $order->invoice }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; margin: 0; padding: 20px; background: #f0f0f0; }
        .label-container { width: 100mm; min-height: 150mm; background: #fff; margin: 0 auto; padding: 15px; border: 1px solid #ccc; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 10px; }
        .logo { font-size: 20px; font-weight: bold; }
        .ekspedisi { font-size: 24px; font-weight: 900; }
        .barcode-box { text-align: center; margin: 15px 0; padding: 10px; border: 1px dashed #000; }
        .barcode { font-family: 'Libre Barcode 39', cursive; font-size: 40px; margin-bottom: 5px; }
        .resi-number { font-size: 16px; font-weight: bold; letter-spacing: 2px; }
        .address-box { display: flex; gap: 10px; margin-bottom: 15px; }
        .address-col { width: 50%; border: 1px solid #000; padding: 10px; }
        .address-col h4 { margin: 0 0 5px 0; font-size: 12px; text-transform: uppercase; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .address-text { font-size: 12px; line-height: 1.4; }
        .items-box { border: 1px solid #000; padding: 10px; margin-bottom: 10px; }
        .items-box h4 { margin: 0 0 5px 0; font-size: 12px; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
        .item { font-size: 11px; display: flex; justify-content: space-between; border-bottom: 1px dashed #eee; padding: 3px 0; }
        .footer { font-size: 10px; text-align: center; color: #555; margin-top: 10px; }
        
        @media print {
            body { background: #fff; padding: 0; }
            .label-container { box-shadow: none; border: none; width: 100%; height: 100%; }
            @page { margin: 0; size: 100mm 150mm; } /* Standar ukuran label resi */
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
</head>
<body onload="window.print()">
    <div class="label-container">
        <div class="header">
            <div class="logo">ERNA Thrifting</div>
            <div class="ekspedisi">JNT / JNE</div>
        </div>
        
        <div class="barcode-box">
            <!-- Ini hanya contoh visual barcode, di dunia nyata menggunakan library pembuat barcode asli -->
            <div class="barcode">*{{ $order->no_resi ?? $order->invoice }}*</div>
            <div class="resi-number">RESI: {{ $order->no_resi ?? 'BELUM ADA RESI' }}</div>
            <div style="font-size: 10px; margin-top: 5px;">KODE BOOKING: {{ $order->invoice }}</div>
        </div>
        
        <div class="address-box">
            <div class="address-col">
                <h4>Penerima:</h4>
                <div class="address-text">
                    <strong>{{ $order->user->name }}</strong><br>
                    {{ $order->user->no_hp ?? '-' }}<br>
                    {{ $order->alamat_pengiriman }}
                </div>
            </div>
            <div class="address-col">
                <h4>Pengirim:</h4>
                <div class="address-text">
                    <strong>ERNA Thrifting</strong><br>
                    0812-3456-7890<br>
                    Jl. Thrift No. 99, Jakarta Pusat, DKI Jakarta
                </div>
            </div>
        </div>
        
        <div class="items-box">
            <h4>Detail Pesanan: (Total: {{ $order->items->sum('jumlah') }} Pcs)</h4>
            @foreach($order->items as $item)
            <div class="item">
                <span>{{ $item->product->nama_produk ?? 'Produk Dihapus' }}</span>
                <span>{{ $item->jumlah }}x</span>
            </div>
            @endforeach
            <div style="font-size: 11px; font-weight: bold; text-align: right; margin-top: 5px; border-top: 1px solid #000; padding-top: 3px;">
                TOTAL BAYAR: Rp {{ number_format($order->total_bayar, 0, ',', '.') }}
            </div>
            <div style="font-size: 10px; margin-top: 5px; font-style: italic;">
                Catatan: {{ $order->catatan ?? '-' }}
            </div>
        </div>
        
        <div class="footer">
            Syarat dan Ketentuan berlaku. Harap buat video unboxing saat membuka paket.
        </div>
    </div>
</body>
</html>
