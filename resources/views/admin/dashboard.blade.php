@extends('admin.layout')

@section('title', 'Dashboard Analytics | ERNA Admin Premium')

@section('custom_css')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root { 
        --bg-body: #F4F7F6; --text-dark: #2C2623; --text-muted: #888888; --white: #ffffff; --border: #EFEBE4; 
        --accent-thrift: #B08968; --gh-1: #FFF9F3; --gh-2: #F0FFF4; --gh-3: #F0F7FF;
        --success: #2ecc71; --danger: #e74c3c;
    }
    
    @keyframes gradientBG { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }

    body { 
        background: linear-gradient(-45deg, var(--bg-body), var(--gh-1), var(--gh-2), var(--gh-3)) !important;
        background-size: 400% 400% !important;
        animation: gradientBG 15s ease infinite !important;
    }

    .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); padding: 15px 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); border: 1px solid rgba(255,255,255,0.5); }
    .header-title h2 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--text-dark); }
    
    /* --- KARTU STATISTIK --- */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 25px; margin-bottom: 30px; }
    .stat-card { 
        background: var(--white); padding: 25px; border-radius: 20px; display: flex; align-items: center; gap: 20px; 
        box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid var(--border); position: relative; 
        transition: 0.4s ease; cursor: pointer;
    }
    .stat-card:hover { transform: translateY(-10px); border-color: var(--accent-thrift); }
    .stat-icon { width: 50px; height: 50px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    
    .icon-revenue { background: #FFF9F3; color: #D4AF37; } 
    .icon-orders { background: #F0F7FF; color: #007BFF; } 
    .icon-products { background: #Fdf2f2; color: #E84C3D; } 
    .icon-customers { background: #F0FFF4; color: #28A745; }
    
    .stat-info h4 { font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
    .stat-info h2 { font-size: 22px; font-weight: 700; color: var(--text-dark); font-family: 'Playfair Display', serif; margin: 0; }
    
    /* Growth Badge */
    .growth-badge { font-size: 9px; font-weight: 800; padding: 4px 8px; border-radius: 20px; margin-top: 5px; display: inline-flex; align-items: center; gap: 4px; }
    .growth-up { background: rgba(46, 204, 113, 0.1); color: var(--success); }
    .growth-down { background: rgba(231, 76, 60, 0.1); color: var(--danger); }

    /* --- CHARTS --- */
    .charts-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px; }
    .panel-card { background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border-radius: 20px; padding: 25px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
    .panel-title { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700; margin-bottom: 20px; color: var(--text-dark); display: flex; align-items: center; gap: 10px; }

    /* --- TABLE --- */
    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 12px 15px; font-size: 10px; text-transform: uppercase; color: var(--text-muted); border-bottom: 1px solid var(--border); }
    td { padding: 12px 15px; font-size: 13px; border-bottom: 1px solid var(--border); }
    .user-pill { display: flex; align-items: center; gap: 10px; }
    .user-avatar { width: 30px; height: 30px; border-radius: 50%; background: var(--gh-1); color: var(--accent-thrift); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px; }

    /* --- NOTIFICATIONS --- */
    .notif-item { display: flex; gap: 15px; padding: 15px; border-bottom: 1px solid var(--border); transition: 0.3s; text-decoration: none; border-radius: 12px; margin-bottom: 5px; }
    .notif-item:hover { background: #fcf9f6; transform: translateX(5px); }
    .notif-item:last-child { border-bottom: none; }
    .notif-icon-circle { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
    .notif-title { font-size: 13px; font-weight: 700; color: var(--text-dark); margin-bottom: 3px; }
    .notif-desc { font-size: 11px; color: var(--text-muted); line-height: 1.4; }
    .notif-time { font-size: 9px; color: #bbb; margin-top: 5px; text-transform: uppercase; font-weight: 600; }

    @media (max-width: 1000px) { .charts-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<header class="top-header">
    <div class="header-title">
        <h2>Dashboard Analytics</h2>
        <p>Wawasan mendalam tentang pertumbuhan bisnis ERNA Thrifting.</p>
    </div>
    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap; justify-content: flex-end;">
        <form action="{{ url('/admin/dashboard') }}" method="GET" style="display: flex; gap: 10px; align-items: center; background: white; padding: 5px 15px; border-radius: 30px; border: 1px solid var(--border); box-shadow: 0 5px 15px rgba(0,0,0,0.02);">
            <i class="fas fa-calendar-alt" style="color: var(--accent-thrift); font-size: 12px;"></i>
            <input type="date" id="filter_start" name="start_date" value="{{ $startDate->format('Y-m-d') }}" style="padding: 5px; font-size: 11px; border: none; background: transparent; color: var(--text-dark); font-family: 'Montserrat', sans-serif;">
            <span style="font-size: 11px; color: var(--text-muted); font-weight: 700;">s/d</span>
            <input type="date" id="filter_end" name="end_date" value="{{ $endDate->format('Y-m-d') }}" style="padding: 5px; font-size: 11px; border: none; background: transparent; color: var(--text-dark); font-family: 'Montserrat', sans-serif;">
            <button type="submit" style="padding: 8px 18px; background: var(--accent-thrift); color: white; border: none; border-radius: 20px; font-size: 11px; font-weight: 700; cursor: pointer; transition: 0.3s; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-sync"></i> Filter Data
            </button>
        </form>

        <div style="display: flex; gap: 8px;">
            <a href="javascript:void(0)" onclick="exportPendapatan('print')" style="padding: 9px 18px; background: #2C2623; color: white; border-radius: 20px; font-size: 11px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 5px 10px rgba(0,0,0,0.1); transition: 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-print"></i> Cetak
            </a>
            <a href="javascript:void(0)" onclick="exportPendapatan('excel')" style="padding: 9px 18px; background: #21BA45; color: white; border-radius: 20px; font-size: 11px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 5px 10px rgba(33,186,69,0.2); transition: 0.3s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                <i class="fas fa-file-csv"></i> Excel
            </a>
        </div>
    </div>
</header>

<script>
    function exportPendapatan(type) {
        let start = document.getElementById('filter_start').value;
        let end = document.getElementById('filter_end').value;
        let url = type === 'print' ? '{{ route("admin.pendapatan.print") }}' : '{{ route("admin.pendapatan.excel") }}';
        window.open(url + '?start_date=' + start + '&end_date=' + end, '_blank');
    }
</script>

<section class="stats-grid">
    {{-- Pendapatan --}}
    <div class="stat-card" onclick="location.href='{{ url('/admin/pesanan') }}'">
        <div class="stat-icon icon-revenue"><i class="fas fa-wallet"></i></div>
        <div class="stat-info">
            <h4>Total Pendapatan</h4>
            <h2>Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
            <div class="growth-badge {{ $revenueGrowth >= 0 ? 'growth-up' : 'growth-down' }}">
                <i class="fas fa-arrow-{{ $revenueGrowth >= 0 ? 'up' : 'down' }}"></i>
                {{ abs($revenueGrowth) }}% vs minggu lalu
            </div>
        </div>
    </div>
    {{-- Average Order Value --}}
    <div class="stat-card">
        <div class="stat-icon icon-orders" style="background: #F0FFF4; color: #28A745;"><i class="fas fa-chart-line"></i></div>
        <div class="stat-info">
            <h4>Average Order Value</h4>
            <h2>Rp {{ number_format($aov, 0, ',', '.') }}</h2>
            <div class="growth-badge growth-up">
                <i class="fas fa-check-circle"></i> Per Transaksi
            </div>
        </div>
    </div>
    {{-- Produk --}}
    <div class="stat-card" onclick="location.href='{{ url('/admin/produk') }}'">
        <div class="stat-icon icon-products"><i class="fas fa-tshirt"></i></div>
        <div class="stat-info">
            <h4>Total Produk</h4>
            <h2>{{ $totalProduk }}</h2>
            <div class="growth-badge {{ $lowStockProducts->count() > 0 ? 'growth-down' : 'growth-up' }}">
                <i class="fas fa-box"></i> {{ $lowStockProducts->count() }} stok kritis
            </div>
        </div>
    </div>
    {{-- Customers --}}
    <div class="stat-card" onclick="location.href='{{ url('/admin/pelanggan') }}'">
        <div class="stat-icon icon-customers"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h4>Loyal Customers</h4>
            <h2>{{ $topCustomers->count() }}</h2>
            <div class="growth-badge growth-up">
                <i class="fas fa-star"></i> Buyer Teraktif
            </div>
        </div>
    </div>
</section>

<section class="charts-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 30px;">
    {{-- Notification Center --}}
    <div class="panel-card">
        <div class="panel-title"><i class="fas fa-bell"></i> Pusat Notifikasi Admin</div>
        <div style="max-height: 300px; overflow-y: auto; padding-right: 10px;">
            @forelse($notifications as $notif)
                <a href="{{ $notif['url'] ?? '#' }}" class="notif-item">
                    <div class="notif-icon-circle" style="background: {{ $notif['color'] }}10; color: {{ $notif['color'] }};">
                        <i class="fas {{ $notif['icon'] }}"></i>
                    </div>
                    <div style="flex: 1;">
                        <div class="notif-title">{{ $notif['title'] }}</div>
                        <div class="notif-desc">{{ $notif['desc'] }}</div>
                        <div class="notif-time">{{ $notif['time']->diffForHumans() }}</div>
                    </div>
                </a>
            @empty
                <div style="text-align: center; padding: 40px; color: var(--text-muted); font-size: 12px;">
                    <i class="far fa-bell-slash" style="font-size: 24px; margin-bottom: 10px; display: block;"></i>
                    Belum ada notifikasi baru.
                </div>
            @endforelse
        </div>
    </div>
    {{-- Quick Tip / Summary --}}
    <div class="panel-card" style="background: linear-gradient(135deg, var(--accent-thrift), #7F5539); color: white;">
        <div class="panel-title" style="color: white;"><i class="fas fa-lightbulb"></i> Ringkasan Performa</div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px;">
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 10px; opacity: 0.8; text-transform: uppercase; font-weight: 700; margin-bottom: 10px;">Conversion Rate</div>
                <div style="font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700;">{{ count($orders) > 0 ? round(($pesananAktif / count($orders)) * 100) : 0 }}%</div>
                <div style="font-size: 9px; opacity: 0.7; margin-top: 5px;">Active vs Total Orders</div>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 20px; border-radius: 15px; text-align: center;">
                <div style="font-size: 10px; opacity: 0.8; text-transform: uppercase; font-weight: 700; margin-bottom: 10px;">Stock Health</div>
                <div style="font-family: 'Playfair Display', serif; font-size: 28px; font-weight: 700;">{{ $lowStockProducts->count() < 5 ? 'Good' : 'Critical' }}</div>
                <div style="font-size: 9px; opacity: 0.7; margin-top: 5px;">Inventory Status</div>
            </div>
        </div>
        <p style="font-size: 11px; margin-top: 20px; opacity: 0.8; line-height: 1.5;">Tips: Segera proses pesanan "Menunggu" untuk meningkatkan kepercayaan pelanggan dan perputaran modal.</p>
    </div>
</section>

<div class="charts-grid">
    {{-- Main Sales Chart --}}
    <div class="panel-card">
        <div class="panel-title"><i class="fas fa-chart-line"></i> Tren Pendapatan (30 Hari Terakhir)</div>
        <div style="height: 350px;"><canvas id="mainSalesChart"></canvas></div>
    </div>
    {{-- Category Pie Chart --}}
    <div class="panel-card">
        <div class="panel-title"><i class="fas fa-chart-pie"></i> Penjualan per Kategori</div>
        <div style="height: 350px;"><canvas id="categoryChart"></canvas></div>
    </div>
    {{-- Status Distribution Chart --}}
    <div class="panel-card">
        <div class="panel-title"><i class="fas fa-tasks"></i> Distribusi Status Pesanan</div>
        <div style="height: 350px;"><canvas id="statusChart"></canvas></div>
    </div>
</div>

<div class="charts-grid" style="grid-template-columns: 1fr 1fr;">
    {{-- Top Customers --}}
    <div class="panel-card">
        <div class="panel-title"><i class="fas fa-crown"></i> 5 Pelanggan Teratas</div>
        <table>
            <thead>
                <tr><th>Pelanggan</th><th>Pesanan</th><th>Total Belanja</th></tr>
            </thead>
            <tbody>
                @foreach($topCustomers as $customer)
                <tr>
                    <td>
                        <div class="user-pill">
                            <div class="user-avatar">{{ substr($customer->name, 0, 2) }}</div>
                            <div style="font-weight: 700;">{{ $customer->name }}</div>
                        </div>
                    </td>
                    <td>{{ $customer->orders_count }} Transaksi</td>
                    <td style="font-weight: 700; color: var(--accent-thrift);">Rp {{ number_format($customer->orders_sum_total_bayar, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{-- Top Products --}}
    <div class="panel-card">
        <div class="panel-title"><i class="fas fa-fire"></i> Produk Terlaris</div>
        <div style="height: 300px;"><canvas id="topProductsChart"></canvas></div>
    </div>
</div>

<div class="panel-card" style="margin-top: 30px;">
    <div class="panel-title"><i class="fas fa-history"></i> Transaksi Terakhir</div>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr><th>Invoice</th><th>Pelanggan</th><th>Status</th><th>Total</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                <tr>
                    <td style="font-weight: 700; color: var(--accent-thrift);">{{ $o->invoice }}</td>
                    <td>{{ $o->user->name ?? 'User' }}</td>
                    <td>
                        <span style="padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 700; background: {{ strtolower($o->status) == 'selesai' ? '#E6FFF1' : '#FFF4E5' }}; color: {{ strtolower($o->status) == 'selesai' ? '#21BA45' : '#FF8C00' }};">
                            {{ $o->status }}
                        </span>
                    </td>
                    <td style="font-weight: 700;">Rp {{ number_format($o->total_bayar, 0, ',', '.') }}</td>
                    <td><a href="{{ url('/admin/pesanan?search='.$o->invoice) }}" style="color: var(--accent-thrift); font-weight: 700; text-decoration: none; font-size: 11px;">Lihat Detail</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // 1. Main Sales Trend Chart
    const salesCtx = document.getElementById('mainSalesChart').getContext('2d');
    const salesGradient = salesCtx.createLinearGradient(0, 0, 0, 400);
    salesGradient.addColorStop(0, 'rgba(176, 137, 104, 0.4)');
    salesGradient.addColorStop(1, 'rgba(176, 137, 104, 0.0)');

    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: {!! json_encode($chartData) !!},
                borderColor: '#B08968',
                borderWidth: 3,
                backgroundColor: salesGradient,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#B08968',
                pointHoverBorderColor: '#fff',
                pointHoverBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: true, color: 'rgba(0,0,0,0.05)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Category Donut Chart
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($catLabels) !!},
            datasets: [{
                data: {!! json_encode($catData) !!},
                backgroundColor: ['#B08968', '#2C2623', '#D4AF37', '#7F5539', '#9C6644', '#EDE0D4'],
                borderWidth: 0,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'bottom', labels: { padding: 20, usePointStyle: true, font: { size: 10 } } }
            }
        }
    });

    // 3. Top Products Bar Chart (Horizontal)
    const prodCtx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(prodCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($topProductsLabels) !!},
            datasets: [{
                label: 'Qty Terjual',
                data: {!! json_encode($topProductsData) !!},
                backgroundColor: '#B08968',
                borderRadius: 10,
                barThickness: 20
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { display: false } }
            }
        }
    });

    // 4. Order Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($statusLabels) !!},
            datasets: [{
                data: {!! json_encode($statusData) !!},
                backgroundColor: ['#2ecc71', '#3498db', '#f1c40f', '#e67e22', '#e74c3c', '#9b59b6'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, font: { size: 10 } } }
            }
        }
    });
</script>
@endsection