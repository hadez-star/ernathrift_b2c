<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->invoice }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; margin: 0; padding: 20px; background: #f9f9f9; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); font-size: 14px; line-height: 24px; background: #fff; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 45px; line-height: 45px; color: #333; font-weight: bold; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td{ border-bottom: 1px solid #eee; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-box { box-shadow: none; border: none; max-width: 100%; padding: 0; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="4">
                    <table>
                        <tr>
                            <td class="title">
                                INVOICE
                            </td>
                            <td>
                                Invoice #: <strong>{{ $order->invoice }}</strong><br>
                                Tanggal: {{ $order->created_at->format('d M Y') }}<br>
                                Status: <strong>{{ strtoupper($order->status) }}</strong>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="4">
                    <table>
                        <tr>
                            <td>
                                <strong>Diterbitkan oleh:</strong><br>
                                ERNA Thrifting<br>
                                Jl. Thrift No. 99, Jakarta<br>
                                admin@ernathrift.com
                            </td>
                            <td>
                                <strong>Ditagihkan kepada:</strong><br>
                                {{ $order->user->name }}<br>
                                {{ $order->user->email }}<br>
                                {{ $order->alamat_pengiriman }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>Item / Produk</td>
                <td class="text-center">Harga Satuan</td>
                <td class="text-center">Qty</td>
                <td class="text-right">Subtotal</td>
            </tr>
            
            @foreach($order->items as $item)
            <tr class="item">
                <td>{{ $item->product->nama_produk ?? 'Produk Terhapus' }}</td>
                <td class="text-center">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-center">{{ $item->jumlah }}</td>
                <td class="text-right">Rp {{ number_format($item->harga_satuan * $item->jumlah, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            
            <tr class="total">
                <td colspan="3" class="text-right">Total Harga Barang:</td>
                <td class="text-right">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td colspan="3" class="text-right">Ongkos Kirim:</td>
                <td class="text-right">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td colspan="3" class="text-right">Diskon Voucher/VIP:</td>
                <td class="text-right">- Rp {{ number_format($order->diskon, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td colspan="3" class="text-right" style="font-size: 18px; color: #B08968;">TOTAL TAGIHAN:</td>
                <td class="text-right" style="font-size: 18px; color: #B08968;">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
            </tr>
        </table>
        
        <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #777;">
            Terima kasih telah berbelanja di ERNA Thrifting.<br>
            Invoice ini sah dan diproses oleh komputer, tidak memerlukan tanda tangan basah.
        </div>
    </div>
</body>
</html>
