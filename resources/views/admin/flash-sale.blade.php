@extends('admin.layout')

@section('title', 'Flash Sale Dinamis | ERNA Admin')

@section('custom_css')
<style>
    :root { --bg-body: #F4F7F6; --text-dark: #2C2623; --text-muted: #888888; --white: #ffffff; --border: #EFEBE4; }
    body { background-color: var(--bg-body) !important; }

    .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; background: var(--white); padding: 15px 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
    .header-title h2 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--text-dark); margin: 0; }
    .admin-avatar { width: 40px; height: 40px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--white); font-weight: 700; }

    .fs-grid { display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px; }

    .flash-sale-widget { background: linear-gradient(135deg, #2C2623, #4A403A); color: var(--white); border-radius: 20px; padding: 40px; box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
    .fs-input-group label { display: block; font-size: 11px; color: #ccc; margin-bottom: 8px; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; }
    .fs-input-group input { width: 100%; padding: 12px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.2); color: white; font-family: 'Montserrat', sans-serif; outline: none; margin-bottom: 20px; font-size: 13px; }
    .fs-input-group input::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }

    .btn-flash-sale { width: 100%; background: #E84C3D; color: white; border: none; padding: 15px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.3s; text-transform: uppercase; font-family: 'Montserrat', sans-serif; letter-spacing: 1px; font-size: 12px;}
    .btn-flash-sale:hover { background: #c0392b; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(232, 76, 61, 0.4); }

    .btn-reset { width: 100%; background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); padding: 15px; border-radius: 12px; font-weight: 600; cursor: pointer; transition: 0.3s; text-transform: uppercase; font-family: 'Montserrat', sans-serif; letter-spacing: 1px; font-size: 12px; margin-top: 15px;}
    .btn-reset:hover { background: rgba(232, 76, 61, 0.2); border-color: rgba(232, 76, 61, 0.5); }

    .product-list-card { background: white; border-radius: 20px; padding: 30px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th { text-align: left; font-size: 11px; color: var(--text-muted); text-transform: uppercase; padding: 10px; border-bottom: 2px solid var(--bg-body); letter-spacing: 1px;}
    td { padding: 15px 10px; font-size: 13px; border-bottom: 1px solid var(--bg-body); vertical-align: middle; }
    
    .form-tambah-produk { background: var(--bg-body); padding: 20px; border-radius: 15px; margin-bottom: 25px; display: grid; grid-template-columns: 2fr 1fr 1fr auto; gap: 20px; align-items: end;}
    .form-tambah-produk select, .form-tambah-produk input { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--border); font-family: 'Montserrat', sans-serif; font-size: 12px;}
    .btn-tambah { background: #2C2623; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 12px; transition: 0.3s; margin-left: 5px;}
    .btn-tambah:hover { background: #4A403A; }
    
    .badge-status { padding: 5px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase;}
    .badge-active { background: rgba(46, 204, 113, 0.2); color: #2ecc71; }
    .badge-inactive { background: rgba(231, 76, 60, 0.2); color: #e74c3c; }

    .btn-hapus { color: #e74c3c; background: rgba(231, 76, 60, 0.1); padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 11px; font-weight: 600; transition: 0.3s;}
    .btn-hapus:hover { background: #e74c3c; color: white; }
</style>
@endsection

@section('content')
<header class="top-header">
    <div class="header-title"><h2>Mesin Flash Sale Dinamis</h2></div>
    <div class="admin-avatar">AD</div>
</header>

<div class="fs-grid">
    <div class="flash-sale-widget">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom:25px;">
            <h2 style="font-family:'Playfair Display', serif; font-size: 22px; margin: 0;">
                <i class="fas fa-bolt" style="color:#F1C40F; margin-right:10px;"></i> Kampanye Aktif
            </h2>
            @if($flashSale && $flashSale->is_active && \Carbon\Carbon::parse($flashSale->end_time)->isFuture())
                <span class="badge-status badge-active">AKTIF MENGUDARA</span>
            @else
                <span class="badge-status badge-inactive">TIDAK AKTIF</span>
            @endif
        </div>
        
        <form action="{{ url('/admin/flash-sale/simpan') }}" method="POST">
            @csrf
            <div class="fs-input-group">
                <label>Nama Kampanye</label>
                <input type="text" name="nama_kampanye" placeholder="Contoh: Gebyar Kemerdekaan ERNA" value="{{ $flashSale->nama_kampanye ?? '' }}" required>
            </div>
            <div class="fs-input-group">
                <label>Waktu Mulai</label>
                <input type="datetime-local" name="start_time" value="{{ $flashSale && $flashSale->start_time ? \Carbon\Carbon::parse($flashSale->start_time)->format('Y-m-d\TH:i') : '' }}" required>
            </div>
            <div class="fs-input-group">
                <label>Waktu Berakhir</label>
                <input type="datetime-local" name="end_time" value="{{ $flashSale && $flashSale->end_time ? \Carbon\Carbon::parse($flashSale->end_time)->format('Y-m-d\TH:i') : '' }}" required>
            </div>
            <button type="submit" class="btn-flash-sale">Simpan & Mulai Flash Sale</button>
        </form>

        <form action="{{ url('/admin/flash-sale/reset') }}" method="GET" id="form-reset-fs">
            <button type="button" class="btn-reset" onclick="confirmReset(event)">Hentikan Flash Sale</button>
        </form>
    </div>

    <div class="product-list-card">
        <h3 style="font-family: 'Playfair Display'; font-size: 18px; margin-bottom: 10px;">Produk Dalam Flash Sale</h3>
        <p style="font-size: 11px; color: var(--text-muted); margin-bottom: 20px;">Tambahkan produk yang ingin Anda diskon besar-besaran selama periode flash sale.</p>
        
        @if($flashSale && $flashSale->is_active)
        <form action="{{ url('/admin/flash-sale/tambah-produk') }}" method="POST" class="form-tambah-produk">
            @csrf
            <div>
                <label style="font-size:10px; color:var(--text-muted); text-transform:uppercase; font-weight:700;">Pilih Produk</label>
                <select name="product_id" required>
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_produk }} (Stok: {{ $p->stok }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-size:10px; color:var(--text-muted); text-transform:uppercase; font-weight:700;">Harga Diskon (Rp)</label>
                <input type="number" name="harga_diskon" placeholder="Harga Coret" required min="1000">
            </div>
            <div>
                <label style="font-size:10px; color:var(--text-muted); text-transform:uppercase; font-weight:700;">Kuota Stok FS</label>
                <input type="number" name="kuota_stok" placeholder="Contoh: 10" required min="1">
            </div>
            <button type="submit" class="btn-tambah">Tambah</button>
        </form>
        @else
        <div style="background: rgba(231, 76, 60, 0.1); padding: 15px; border-radius: 10px; color: #e74c3c; font-size: 12px; margin-bottom: 20px;">
            <i class="fas fa-info-circle"></i> Silakan atur dan aktifkan kampanye Flash Sale di panel sebelah kiri terlebih dahulu.
        </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Harga Asli</th>
                    <th>Harga Flash Sale</th>
                    <th>Kuota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($flashSaleItems ?? [] as $item)
                <tr>
                    <td style="font-weight: 600;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <img src="{{ asset($item->product->gambar) }}" style="width:30px; height:30px; object-fit:cover; border-radius:5px;">
                            {{ $item->product->nama_produk }}
                        </div>
                    </td>
                    <td style="color: var(--text-muted); text-decoration: line-through; font-size:11px;">Rp {{ number_format($item->product->harga, 0, ',', '.') }}</td>
                    <td style="color: #E84C3D; font-weight: 700;">Rp {{ number_format($item->harga_diskon, 0, ',', '.') }}</td>
                    <td><span style="background: var(--bg-body); padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight:600;">{{ $item->kuota_stok }}</span></td>
                    <td>
                        <a href="{{ url('/admin/flash-sale/hapus-produk/' . $item->id) }}" class="btn-hapus" onclick="confirmDelete(event, '{{ $item->product->nama_produk }}')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #aaa; padding: 30px;">Belum ada produk terdaftar dalam Flash Sale.</td>
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
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#B08968'
        });
    </script>
    @endif
    @if(session('error'))
    <script>
        Swal.fire({
            title: 'Oops!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonColor: '#E84C3D'
        });
    </script>
    @endif

    <script>
        function confirmReset(event) {
            event.preventDefault();
            Swal.fire({
                title: `<div style="font-family: 'Playfair Display', serif; font-size: 26px; color: #2C2623;">Hentikan Flash Sale?</div>`,
                html: `<p style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #888888;">Apakah Anda yakin ingin menghentikan kampanye ini dan menghapus semua produk di dalamnya?</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D9534F',
                cancelButtonColor: '#EFEBE4',
                confirmButtonText: '<span style="font-family:\'Montserrat\', sans-serif; font-weight:600; font-size:13px; letter-spacing:1px;">YA, HENTIKAN!</span>',
                cancelButtonText: '<span style="font-family:\'Montserrat\', sans-serif; font-weight:600; font-size:13px; color:#2C2623; letter-spacing:1px;">BATAL</span>',
                customClass: { popup: 'premium-swal-popup' },
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-reset-fs').submit();
                }
            });
        }
    </script>
@endsection