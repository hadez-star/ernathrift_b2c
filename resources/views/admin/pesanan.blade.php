@extends('admin.layout')

@section('title', 'Kelola Pesanan - Admin ERNA')

@section('custom_css')
<style>
    .card { background: white; padding: 30px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #eee; }
    
    .header-action { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px; }
    .header-action h2 { font-weight: 700; color: #2C2623; margin: 0; }
    
    .search-box { display: flex; gap: 8px; }
    .search-input { padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 12px; width: 250px; outline: none; transition: 0.3s; }
    .search-input:focus { border-color: #B08968; box-shadow: 0 0 0 3px rgba(176, 137, 104, 0.1); }
    .btn-search { background: #B08968; color: white; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 12px; transition: 0.3s; }
    .btn-search:hover { background: #8c6b5d; }
    .btn-reset { background: #f4f4f4; color: #555; border: none; padding: 10px 15px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 12px; text-decoration: none; transition: 0.3s; display: flex; align-items: center; }
    .btn-reset:hover { background: #e0e0e0; }

    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 18px 15px; border-bottom: 2px solid #f4f4f4; font-size: 11px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
    td { padding: 18px 15px; border-bottom: 1px solid #f4f4f4; font-size: 13px; vertical-align: top; }
    
    .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 10px; font-weight: 800; text-transform: uppercase; display: inline-block; }
    .status-menunggu { background: #fdf2e9; color: #e67e22; } 
    .status-dikemas { background: #fff3e0; color: #f39c12; }
    .status-dikirim { background: #e3f2fd; color: #3498db; }
    .status-selesai { background: #e8f5e9; color: #27ae60; }
    .status-dibatalkan { background: #fdeaea; color: #e74c3c; }
    
    .btn-update { background: none; border: 1px solid #ddd; padding: 8px 12px; border-radius: 8px; cursor: pointer; font-size: 12px; font-family: inherit; font-weight: 600; transition: 0.2s; width: 100%; }
    .btn-update:hover { border-color: #B08968; color: #B08968; }
    
    /* CSS TAMBAHAN: Efek saat dropdown terkunci */
    .btn-update:disabled { background: #fdfdfd; color: #aaa; border-color: #eee; cursor: not-allowed; }
    .btn-update:disabled:hover { border-color: #eee; color: #aaa; }

    .product-list { margin: 0; padding-left: 15px; color: #444; font-size: 12px; }
    .product-list li { margin-bottom: 8px; }
    .product-cat-tag { display: inline-block; background: #f4f4f4; color: #888; font-size: 9px; padding: 2px 6px; border-radius: 4px; margin-top: 3px; letter-spacing: 0.5px; }
    
    /* CSS UNTUK CATATAN */
    .note-box { margin-top: 10px; padding: 10px; background: #FFF9E6; border-left: 4px solid #F1C40F; border-radius: 4px; font-size: 11px; color: #5D5337; line-height: 1.4; }

    /* CSS UNTUK TOMBOL CETAK */
    .btn-action-print { display: inline-block; padding: 6px; background: #f4f4f4; border: 1px solid #ddd; color: #555; border-radius: 6px; font-size: 10px; font-weight: 600; text-decoration: none; transition: 0.2s; width: 48%; text-align: center; margin-top: 5px; box-sizing: border-box; }
    .btn-action-print:hover { background: #e0e0e0; color: #333; }
</style>
@endsection

@section('content')
<div class="card">
    
    <div class="header-action">
        <h2>Daftar Pesanan Pelanggan</h2>
        
        <form action="{{ url('/admin/pesanan') }}" method="GET" class="search-box">
            <input type="text" name="cari_pelanggan" class="search-input" placeholder="Ketik nama pelanggan..." value="{{ request('cari_pelanggan') }}">
            <button type="submit" class="btn-search"><i class="fas fa-search"></i> Cari</button>
            
            @if(request('cari_pelanggan'))
                <a href="{{ url('/admin/pesanan') }}" class="btn-reset"><i class="fas fa-times"></i></a>
            @endif
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>INVOICE / TANGGAL</th>
                    <th>PELANGGAN</th>
                    <th style="width: 25%;">DETAIL PRODUK</th>
                    <th>TOTAL BAYAR</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                <tr>
                    <td>
                        <span style="font-weight: 700; color: #333;">{{ $o->invoice }}</span><br>
                        <span style="font-size: 11px; color: #aaa;">{{ $o->created_at->format('d M Y, H:i') }}</span>
                    </td>
                    <td>
                        <span style="font-weight: 600;">{{ $o->user->name ?? 'User Dihapus' }}</span><br>
                        <span style="font-size: 11px; color: #888;">{{ $o->alamat_pengiriman }}</span>
                        
                        @if($o->catatan)
                        <div class="note-box">
                            <i class="fas fa-sticky-note"></i> <strong>Catatan:</strong><br>
                            <em>"{{ $o->catatan }}"</em>
                        </div>
                        @endif

                        @if($o->alasan_retur)
                        <div class="note-box" style="background: #fdeaea; border-color: #e74c3c; color: #c0392b; margin-top: 10px;">
                            <i class="fas fa-undo"></i> <strong>Pengajuan Retur:</strong><br>
                            <em>"{{ $o->alasan_retur }}"</em>
                            
                            @if($o->bukti_retur)
                                <div style="margin-top: 10px;">
                                    <p style="font-size: 10px; font-weight: 700; margin-bottom: 5px;">BUKTI KERUSAKAN:</p>
                                    <img src="{{ asset($o->bukti_retur) }}" style="width: 100%; border-radius: 8px; cursor: pointer; border: 1px solid #e74c3c;" onclick="window.open(this.src)">
                                </div>
                            @endif

                            <form action="/admin/pesanan/update/{{ $o->id }}" method="POST" style="margin-top: 8px;">
                                @csrf
                                <input type="hidden" name="status" value="{{ $o->status }}">
                                <select name="status_retur" onchange="this.form.submit()" style="width: 100%; padding: 5px; font-size: 11px; border-radius: 4px; border: 1px solid #e74c3c; outline: none; background: #fff; cursor: pointer;">
                                    <option value="Diajukan" {{ $o->status_retur == 'Diajukan' ? 'selected' : '' }}>-- Status Retur (Diajukan) --</option>
                                    <option value="Disetujui" {{ $o->status_retur == 'Disetujui' ? 'selected' : '' }}>Setujui Retur</option>
                                    <option value="Ditolak" {{ $o->status_retur == 'Ditolak' ? 'selected' : '' }}>Tolak Retur</option>
                                </select>
                            </form>
                        </div>
                        @endif
                    </td>
                    <td>
                        @php
                            $orderItems = \App\Models\OrderItem::with('product')->where('order_id', $o->id)->get();
                        @endphp
                        <ul class="product-list">
                            @foreach($orderItems as $item)
                                <li>
                                    <strong>{{ $item->jumlah }}x</strong> {{ $item->product->nama_produk ?? 'Produk Telah Dihapus' }}
                                    <br>
                                    <span class="product-cat-tag">{{ $item->product->kategori ?? 'Tanpa Kategori' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <span style="font-weight: 700; color: #B08968;">Rp {{ number_format($o->total_bayar, 0, ',', '.') }}</span>
                        @if(isset($o->metode_pembayaran))
                            <br><span style="font-size: 10px; color: #aaa; text-transform: uppercase;">Via: {{ $o->metode_pembayaran }}</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $badgeClass = strtolower(explode(' ', trim($o->status))[0]);
                        @endphp
                        <span class="status-badge status-{{ $badgeClass }}">
                            {{ $o->status }}
                        </span>
                    </td>
                    <td>
                        <form action="/admin/pesanan/update/{{ $o->id }}" method="POST" style="display:flex; flex-direction:column; gap:8px;">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="btn-update" {{ strtolower($o->status) == 'selesai' ? 'disabled' : '' }}>
                                <option value="Menunggu Pembayaran" {{ $o->status == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Bayar</option>
                                <option value="Dikemas" {{ $o->status == 'Dikemas' ? 'selected' : '' }}>Dikemas</option>
                                <option value="Dikirim" {{ $o->status == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                                <option value="Selesai" {{ $o->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="Dibatalkan" {{ $o->status == 'Dibatalkan' ? 'selected' : '' }}>Batalkan</option>
                            </select>
                            
                            @if(in_array($o->status, ['Dikemas', 'Dikirim']))
                                <input type="text" name="no_resi" value="{{ $o->no_resi }}" placeholder="Nomor Resi" style="width: 100%; padding: 6px 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; font-family: inherit; transition: 0.3s; outline:none;" onfocus="this.style.borderColor='#B08968';" onblur="this.style.borderColor='#ddd'; this.form.submit();">
                            @endif
                        </form>
                        
                        <div style="display: flex; gap: 4%; justify-content: space-between; margin-top: 8px;">
                            <a href="{{ url('/admin/pesanan/cetak-resi/' . $o->id) }}" target="_blank" class="btn-action-print"><i class="fas fa-barcode"></i> Resi</a>
                            <a href="{{ url('/admin/pesanan/cetak-invoice/' . $o->id) }}" target="_blank" class="btn-action-print"><i class="fas fa-file-invoice"></i> Invoice</a>
                        </div>
                        
                        {{-- Teks penanda untuk pesanan yang sudah selesai --}}
                        @if(strtolower($o->status) == 'selesai')
                            <span style="font-size: 10px; color: #aaa; display: block; margin-top: 6px; text-align: center;">Pesanan difinalisasi</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; color: #aaa; padding: 60px;">
                        @if(request('cari_pelanggan'))
                            <i class="fas fa-search" style="font-size: 30px; color: #ddd; margin-bottom: 10px; display: block;"></i>
                            Pelanggan dengan nama "<strong>{{ request('cari_pelanggan') }}</strong>" tidak ditemukan.
                        @else
                            <i class="fas fa-box-open" style="font-size: 30px; color: #ddd; margin-bottom: 10px; display: block;"></i>
                            Belum ada pesanan yang masuk.
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
    @if(session('success'))
        <script>
            Swal.fire({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3500, timerProgressBar: true, background: '#ffffff',
                html: `
                    <div style="display: flex; align-items: center; gap: 15px; text-align: left;">
                        <div style="background: #E6FFF1; min-width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-check" style="color: #21BA45; font-size: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-family: 'Montserrat', sans-serif; font-size: 14px; font-weight: 700; color: #2C2623; margin: 0 0 4px 0;">Berhasil</h4>
                            <p style="font-family: 'Montserrat', sans-serif; font-size: 12px; color: #777; margin: 0;">{{ session('success') }}</p>
                        </div>
                    </div>
                `,
                customClass: { popup: 'premium-admin-toast' }
            });
        </script>
    @endif
@endsection