<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Eksekutif Penjualan - ERNA THRIFTING</title>
    <style>
        @page { margin: 1.5cm; }
        body { 
            font-family: 'Helvetica', Arial, sans-serif; 
            font-size: 10pt; 
            color: #333; 
            line-height: 1.5; 
            margin: 0; 
            padding: 0;
        }
        
        /* Official Header (Kop Surat) */
        .kop-surat {
            border-bottom: 4px double #111;
            padding-bottom: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .kop-surat .brand {
            font-size: 28pt;
            font-weight: 900;
            color: #111;
            margin: 0;
            letter-spacing: 3px;
        }
        .kop-surat .tagline {
            font-size: 10pt;
            color: #D4AF37;
            text-transform: uppercase;
            letter-spacing: 5px;
            font-weight: bold;
            margin: 5px 0;
        }
        .kop-surat .address {
            font-size: 9pt;
            color: #666;
            margin-top: 5px;
        }

        .report-meta {
            width: 100%;
            margin-bottom: 30px;
        }
        .report-title {
            font-size: 16pt;
            font-weight: bold;
            color: #111;
            text-decoration: underline;
            margin-bottom: 10px;
        }
        .report-info {
            font-size: 9pt;
            color: #555;
        }

        /* Summary Dashboard */
        .summary-container {
            margin-bottom: 30px;
        }
        .summary-box {
            width: 100%;
            border-collapse: separate;
            border-spacing: 15px 0;
        }
        .summary-card {
            background: #fcf9f5;
            border: 1px solid #e0d5c1;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            width: 25%;
        }
        .card-label {
            font-size: 8pt;
            color: #8a7a60;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .card-value {
            font-size: 13pt;
            font-weight: bold;
            color: #111;
        }

        /* Tables */
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            color: #111;
            margin-top: 20px;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #D4AF37;
            display: inline-block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background: #111;
            color: #fff;
            text-align: left;
            padding: 12px 10px;
            font-size: 8.5pt;
            text-transform: uppercase;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 9pt;
        }
        tr:nth-child(even) { background-color: #fafafa; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .highlight { font-weight: bold; color: #111; }

        /* Signature Section */
        .signature-table {
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            width: 300px;
            text-align: center;
        }
        .signature-space {
            height: 80px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 7.5pt;
            color: #aaa;
            padding: 10px 0;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <h1 class="brand">ERNA THRIFTING</h1>
        <div class="tagline">Premium Thrift & Curated Style</div>
        <div class="address">
            Jl. Sudirman No. 123, Jakarta Selatan | Telp: (021) 555-0123 | Email: admin@ernathrift.com
        </div>
    </div>

    <div class="report-meta">
        <div class="report-title">LAPORAN EKSEKUTIF PENJUALAN</div>
        <table style="width: 100%;">
            <tr>
                <td style="border: none; padding: 0;" class="report-info">
                    ID Laporan: RPT-{{ date('Ymd') }}-{{ rand(100, 999) }}<br>
                    Tanggal Cetak: {{ date('d F Y, H:i') }}
                </td>
                <td style="border: none; padding: 0; text-align: right;" class="report-info">
                    Periode Laporan:<br>
                    <strong>
                        @if(request('start_date')) 
                            {{ Carbon\Carbon::parse(request('start_date'))->format('d M Y') }} s/d {{ Carbon\Carbon::parse(request('end_date'))->format('d M Y') }}
                        @else 
                            Keseluruhan Waktu 
                        @endif
                    </strong>
                </td>
            </tr>
        </table>
    </div>

    <div class="summary-container">
        <table class="summary-box">
            <tr>
                <td class="summary-card">
                    <div class="card-label">Total Pendapatan</div>
                    <div class="card-value" style="color: #27ae60;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </td>
                <td class="summary-card">
                    <div class="card-label">Volume Pesanan</div>
                    <div class="card-value">{{ $totalPesanan }} Trx</div>
                </td>
                <td class="summary-card">
                    <div class="card-label">Avg. Order Value</div>
                    <div class="card-value">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</div>
                </td>
                <td class="summary-card">
                    <div class="card-label">Unit Terjual</div>
                    <div class="card-value">{{ $laporanProduk->sum('total_terjual') }} Pcs</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">I. Performa Berdasarkan Kategori</div>
    <table>
        <thead>
            <tr>
                <th>Kategori Produk</th>
                <th class="text-center">Volume (Pcs)</th>
                <th class="text-right">Omzet Penjualan</th>
                <th class="text-center">Kontribusi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanKategori as $cat)
            <tr>
                <td class="highlight">{{ $cat->kategori }}</td>
                <td class="text-center">{{ $cat->total_pcs }}</td>
                <td class="text-right">Rp {{ number_format($cat->total_omzet, 0, ',', '.') }}</td>
                <td class="text-center">
                    <div style="background: #eee; border-radius: 5px; height: 10px; width: 60px; display: inline-block; margin-right: 5px;">
                        <div style="background: #D4AF37; height: 100%; border-radius: 5px; width: {{ $totalPendapatan > 0 ? ($cat->total_omzet / $totalPendapatan) * 100 : 0 }}%;"></div>
                    </div>
                    {{ $totalPendapatan > 0 ? round(($cat->total_omzet / $totalPendapatan) * 100, 1) : 0 }}%
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">II. Peringkat Penjualan Produk (Top 25)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 20px;">#</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th class="text-center">Unit</th>
                <th class="text-right">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanProduk->take(25) as $index => $item)
            <tr>
                <td class="text-center" style="color: #888;">{{ $index + 1 }}</td>
                <td class="highlight">{{ $item->nama_produk }}</td>
                <td>{{ $item->kategori }}</td>
                <td class="text-center">{{ $item->total_terjual }}</td>
                <td class="text-right highlight">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td style="border: none;"></td>
            <td style="border: none;" class="signature-box">
                <p>Jakarta, {{ date('d F Y') }}</p>
                <p>Mengetahui,</p>
                <p><strong>Manager Operasional</strong></p>
                <div class="signature-space"></div>
                <p class="signature-name">ERNA SULISTIOWATI</p>
                <p style="font-size: 8pt; color: #888;">NIP. 19900515 202301 2 001</p>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh Sistem Informasi ERNA THRIFTING.<br>
        Seluruh data yang disajikan bersifat valid sesuai dengan database transaksi real-time.
    </div>

</body>
</html>