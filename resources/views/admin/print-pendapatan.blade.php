<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan ERNA Thrifting</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; color: #333; }
        h2 { text-align: center; margin-bottom: 5px; }
        p.periode { text-align: center; font-size: 14px; color: #666; margin-bottom: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; font-size: 14px; }
        th { background-color: #f4f4f4; font-weight: bold; }
        .total-row { font-weight: bold; background-color: #f9f9f9; }
        .total-row td { text-align: right; }
        .text-right { text-align: right; }
        @media print {
            body { padding: 0; }
            button { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <h2>Laporan Pendapatan ERNA Thrifting</h2>
    <p class="periode">Periode: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>No. Invoice</th>
                <th>Pelanggan</th>
                <th class="text-right">Total Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $order->invoice }}</td>
                <td>{{ $order->user->name ?? 'User' }}</td>
                <td class="text-right">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Tidak ada data pendapatan pada periode ini.</td>
            </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="3">TOTAL PENDAPATAN</td>
                <td>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background-color: #333; color: #fff; border: none; cursor: pointer; font-size: 16px;">Cetak Laporan</button>
    </div>
</body>
</html>
