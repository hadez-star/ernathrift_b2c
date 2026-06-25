@extends('admin.layout')

@section('title', 'Kode Voucher | ERNA Admin')

@section('custom_css')
<style>
    :root { --bg-body: #F4F7F6; --text-dark: #2C2623; --text-muted: #888888; --white: #ffffff; --border: #EFEBE4; }
    body { background-color: var(--bg-body) !important; }

    .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; background: var(--white); padding: 15px 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
    .header-title h2 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--text-dark); margin-bottom: 5px; }
    .admin-avatar { width: 40px; height: 40px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--white); font-weight: 700; }

    .voucher-layout { display: grid; grid-template-columns: 1.2fr 2fr; gap: 30px; }
    .panel-card { background: var(--white); border-radius: 20px; padding: 30px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
    .panel-title { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700; margin-bottom: 25px; display: flex; align-items: center; color: var(--text-dark); }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; }
    .form-group input, .form-group select { width: 100%; padding: 12px 15px; border: 1px solid var(--border); border-radius: 10px; font-family: 'Montserrat', sans-serif; outline: none; background: var(--bg-body); transition: 0.3s; font-size: 13px; box-sizing: border-box; }
    .form-group input:focus, .form-group select:focus { border-color: var(--accent); background: var(--white); }
    
    .btn-submit { background: var(--dark-bg, #231F1E); color: var(--white); border: none; padding: 15px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: 0.3s; width: 100%; text-transform: uppercase; letter-spacing: 1px; font-size: 12px; margin-top: 10px; font-family: 'Montserrat', sans-serif; }
    .btn-submit:hover { background: var(--accent); }

    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 15px; border-bottom: 2px solid var(--border); color: var(--text-muted); font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
    td { padding: 15px; border-bottom: 1px solid var(--border); font-size: 13px; font-weight: 500; vertical-align: middle; color: var(--text-dark); }

    .badge-target { padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase; display: inline-block; }
    .bg-all { background: #EBF5FF; color: #007BFF; }
    .bg-vip { background: #FFF9E6; color: #D4AF37; }
    
    .btn-delete { background: #FFEAEA; color: #D9534F; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 11px; transition: 0.3s; font-family: 'Montserrat', sans-serif; text-decoration: none; }
    .btn-delete:hover { background: #D9534F; color: white; }
    
    @media (max-width: 768px) { .voucher-layout { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<header class="top-header">
    <div class="header-title">
        <h2>Kelola Kode Voucher</h2>
        <p style="font-size: 12px; color: var(--text-muted); margin-top: 5px; margin-bottom: 0;">Buat promo untuk pelanggan dan tentukan masa berlakunya.</p>
    </div>
    <div class="admin-avatar">AD</div>
</header>

<section class="voucher-layout">
    <div class="panel-card" style="height: fit-content;">
        <div class="panel-title"><i class="fas fa-plus-circle" style="color: var(--accent); margin-right:10px;"></i> Buat Voucher Baru</div>
        
        <form action="{{ url('/admin/voucher/simpan') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label>Kode Voucher (Misal: THRIFT20)</label>
                <input type="text" name="code" style="text-transform: uppercase;" required placeholder="Masukkan Kode">
            </div>
            
            <div class="form-group">
                <label>Tipe Potongan</label>
                <select name="type" required>
                    <option value="fixed">Nominal Tetap (Rp)</option>
                    <option value="percent">Persentase (%)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Nilai Potongan (Rp / %)</label>
                <input type="number" name="reward_amount" required placeholder="Contoh: 20000 atau 10">
            </div>

            <div class="form-group">
                <label>Minimal Belanja (Rp)</label>
                <input type="number" name="min_spend" value="0" required placeholder="Contoh: 50000">
            </div>

            <div class="form-group">
                <label>Kuota Penggunaan (-1 untuk Tanpa Batas)</label>
                <input type="number" name="limit" value="-1" required placeholder="Contoh: 100">
            </div>

            <div class="form-group">
                <label>Target Pengguna (Keterangan)</label>
                <select name="expiry_date" required>
                    <option value="Semua Pengguna">Semua Pengguna</option>
                    <option value="Khusus VIP">Khusus VIP</option>
                </select>
            </div>

            <div class="form-group">
                <label>Batas Waktu Berlaku</label>
                <input type="datetime-local" name="valid_until" required>
            </div>
            
            <button type="submit" class="btn-submit">Simpan Voucher</button>
        </form>
    </div>
    
    <div class="panel-card">
        <div class="panel-title"><i class="fas fa-ticket-alt" style="color: var(--accent); margin-right:10px;"></i> Daftar Voucher Aktif</div>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Potongan</th>
                        <th>Min. Belanja</th>
                        <th>Kuota</th>
                        <th>Target</th>
                        <th>Berlaku Sampai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $v)
                    <tr>
                        <td style="font-weight: 700; color: var(--accent); letter-spacing: 1px;">{{ strtoupper($v->code) }}</td>
                        <td>
                            @if($v->type == 'percent')
                                {{ $v->reward_amount }}%
                            @else
                                Rp {{ number_format($v->reward_amount, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>Rp {{ number_format($v->min_spend, 0, ',', '.') }}</td>
                        <td>{{ $v->limit == -1 ? '∞' : $v->limit }}</td>
                        <td>
                            <span class="badge-target {{ str_contains(strtolower($v->expiry_date), 'vip') ? 'bg-vip' : 'bg-all' }}">
                                {{ $v->expiry_date }}
                            </span>
                        </td>
                        <td style="font-size: 11px; color: #666;">
                            @php
                                try {
                                    $tanggal = $v->valid_until ? \Carbon\Carbon::parse($v->valid_until)->format('d M Y, H:i') : 'Tidak diatur';
                                } catch (\Exception $e) {
                                    $tanggal = 'Format Error';
                                }
                            @endphp
                            {{ $tanggal }}
                        </td>
                        <td>
                            <a href="{{ url('/admin/voucher/hapus/'.$v->id) }}" class="btn-delete" onclick="confirmDelete(event, 'Voucher {{ strtoupper($v->code) }}')">Hapus</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; color: #aaa; padding: 30px;">Belum ada voucher yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection