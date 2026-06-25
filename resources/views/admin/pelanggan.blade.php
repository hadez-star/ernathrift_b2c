@extends('admin.layout')

@section('title', 'Data Pelanggan | ERNA Admin')

@section('custom_css')
<style>
    :root { --bg-body: #F4F7F6; --text-dark: #2C2623; --text-muted: #888888; --white: #ffffff; --border: #EFEBE4; }
    body { background-color: var(--bg-body) !important; }

    .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; background: var(--white); padding: 15px 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
    .header-title h2 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--text-dark); margin-bottom: 5px; }
    .admin-avatar { width: 40px; height: 40px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--white); font-weight: 700; }

    .panel-card { background: var(--white); border-radius: 20px; padding: 30px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
    
    table { width: 100%; border-collapse: collapse; } 
    th { text-align: left; padding: 15px; border-bottom: 2px solid var(--border); font-size: 11px; text-transform: uppercase; color: var(--text-muted);} 
    td { padding: 15px; border-bottom: 1px solid var(--border); font-size: 13px;}
    
    .badge { padding: 6px 12px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase; display: inline-block; }
    .badge-vip-gold { background: #FFF9E6; color: #D4AF37; border: 1px solid #F5E6B3; }
    .badge-vip-silver { background: #F8F9FA; color: #6C757D; border: 1px solid #DEE2E6; }
    .badge-reguler { background: #f4f4f4; color: #888; }
    
    .user-details strong { display: block; color: var(--text-dark); font-size: 14px; font-weight: 700; margin-bottom: 3px; }
    .user-details span { font-size: 11px; color: var(--text-muted); }
    
    .btn-action { background: var(--white); border: 1px solid var(--accent); color: var(--accent); padding: 8px 15px; border-radius: 8px; cursor: pointer; font-size: 11px; font-weight: 600; transition: 0.3s; }
    .btn-action:hover { background: var(--accent); color: var(--white); }

    /* CSS Khusus SweetAlert Modal Detail */
    .swal-customer-detail { text-align: left !important; }
    .detail-row { display: flex; justify-content: space-between; border-bottom: 1px dashed #eae5df; padding: 12px 0; font-size: 13px; align-items: center; }
    .detail-row:last-child { border-bottom: none; }
    .detail-label { color: #888; font-weight: 600; font-size: 11px; text-transform: uppercase; display: flex; align-items: center; gap: 8px; }
    .detail-value { font-weight: 600; color: #333; text-align: right; max-width: 60%; word-wrap: break-word; }
</style>
@endsection

@section('content')
<header class="top-header">
    <div class="header-title">
        <h2>Data Pelanggan & Transaksi</h2>
        <p style="color: var(--text-muted); font-size: 13px; margin:0;">Kelola informasi kontak dan pantau riwayat belanja pelanggan Anda.</p>
    </div>
    <div class="admin-avatar">AD</div>
</header>

<section class="panel-card">
    <h3 style="margin-bottom:20px; margin-top:0; font-family:'Playfair Display'; color: #2C2623;">Daftar Pelanggan Terdaftar</h3>
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Status Member</th>
                    <th>Saldo ERNA Pay</th>
                    <th>Tanggal Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users ?? [] as $u)
                @php
                    // Menarik data pesanan khusus untuk user ini
                    $orders = \App\Models\Order::where('user_id', $u->id)->get();
                    $totalBelanja = $orders->sum('total_bayar');
                    $jumlahOrder = $orders->count();
                    
                    // Pengecekan data opsional
                    $alamat = $u->alamat ? $u->alamat . ' No. ' . $u->no_rumah : 'Belum diatur';
                    $noHp = $u->no_hp ?? 'Belum diatur';
                    $metode = $totalBelanja > 0 ? 'ERNA Pay' : 'Belum ada transaksi';
                @endphp
                <tr>
                    <td>
                        <div class="user-details">
                            <strong>{{ $u->name }}</strong>
                            <span>{{ $u->email }}</span>
                        </div>
                    </td>
                    <td>
                        @if($u->vip_paket == 'GOLD')
                            <span class="badge badge-vip-gold"><i class="fas fa-crown"></i> VIP Gold</span>
                        @elseif($u->vip_paket == 'SILVER')
                            <span class="badge badge-vip-silver"><i class="fas fa-medal"></i> VIP Silver</span>
                        @else
                            <span class="badge badge-reguler">Reguler</span>
                        @endif
                    </td>
                    <td style="font-weight: 600; color: var(--accent);">Rp {{ number_format($u->saldo, 0, ',', '.') }}</td>
                    <td style="font-size: 12px; color: #888;">{{ $u->created_at->format('d M Y') }}</td>
                    <td>
                        <button class="btn-action" onclick="showDetail(
                            '{{ addslashes($u->name) }}',
                            '{{ addslashes($u->email) }}',
                            '{{ addslashes($noHp) }}',
                            '{{ addslashes($alamat) }}',
                            '{{ $u->vip_paket ?? 'REGULER' }}',
                            '{{ number_format($totalBelanja, 0, ',', '.') }}',
                            '{{ $jumlahOrder }}',
                            '{{ $metode }}'
                        )">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align: center; color: #aaa; padding: 40px;">Belum ada pelanggan yang mendaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function showDetail(nama, email, no_hp, alamat, vip, total_belanja, jml_order, metode) {
        // Label Badge untuk VIP / REGULER
        let badgeHtml = '';
        if(vip === 'GOLD') {
            badgeHtml = `<span style="background: #FFF9E6; color: #D4AF37; border: 1px solid #F5E6B3; padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase;"><i class="fas fa-crown"></i> VIP GOLD</span>`;
        } else if(vip === 'SILVER') {
            badgeHtml = `<span style="background: #F8F9FA; color: #6C757D; border: 1px solid #DEE2E6; padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase;"><i class="fas fa-medal"></i> VIP SILVER</span>`;
        } else {
            badgeHtml = `<span style="background: #F4F1EA; color: #888; padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase;">REGULER</span>`;
        }

        // Inisial Nama untuk Avatar
        let inisial = nama.charAt(0).toUpperCase();

        Swal.fire({
            title: `<div style="font-family: 'Playfair Display', serif; font-size: 26px; border-bottom: 2px solid #EFEBE4; padding-bottom: 15px; margin-bottom: 10px; color: #2C2623;">Profil Pelanggan</div>`,
            html: `
                <div class="swal-customer-detail" style="font-family: 'Montserrat', sans-serif;">
                    
                    <div style="text-align: center; margin-bottom: 25px;">
                        <div style="width: 80px; height: 80px; background: #B08968; color: white; border-radius: 50%; font-size: 32px; line-height: 80px; margin: 0 auto 15px; font-weight: 700; box-shadow: 0 5px 15px rgba(176,137,104,0.3);">
                            ${inisial}
                        </div>
                        <h3 style="margin: 0 0 10px 0; font-size: 18px; color: #2C2623; font-weight: 700;">${nama}</h3>
                        <div>${badgeHtml}</div>
                    </div>

                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-envelope" style="color: #B08968;"></i> Email</span>
                        <span class="detail-value">${email}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-phone-alt" style="color: #B08968;"></i> Nomor HP</span>
                        <span class="detail-value">${no_hp}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label"><i class="fas fa-map-marker-alt" style="color: #B08968;"></i> Alamat Utama</span>
                        <span class="detail-value">${alamat}</span>
                    </div>
                    
                    <div style="margin-top: 25px; padding: 20px; background: #FDFBF7; border-radius: 12px; border: 1px solid #EFEBE4;">
                        <h4 style="margin: 0 0 15px 0; font-size: 12px; color: #2C2623; text-transform: uppercase; text-align: center; letter-spacing: 2px; font-weight: 700;">Statistik Pembelian</h4>
                        
                        <div class="detail-row" style="border: none; padding: 5px 0;">
                            <span class="detail-label"><i class="fas fa-shopping-bag" style="color: #888;"></i> Total Pesanan</span>
                            <span class="detail-value">${jml_order} Transaksi</span>
                        </div>
                        <div class="detail-row" style="border: none; padding: 5px 0;">
                            <span class="detail-label"><i class="fas fa-wallet" style="color: #888;"></i> Sering Pakai</span>
                            <span class="detail-value">${metode}</span>
                        </div>
                        
                        <div style="height: 1px; background: #EFEBE4; margin: 10px 0;"></div>
                        
                        <div class="detail-row" style="border: none; padding: 5px 0;">
                            <span class="detail-label" style="color: #2C2623;">Total Dihabiskan</span>
                            <span class="detail-value" style="color: #27ae60; font-size: 16px; font-weight: 800;">Rp ${total_belanja}</span>
                        </div>
                    </div>

                </div>
            `,
            showCloseButton: true,
            showConfirmButton: false,
            width: '500px',
            background: '#ffffff',
            customClass: {
                popup: 'premium-swal-popup'
            }
        });
    }
</script>
@endsection