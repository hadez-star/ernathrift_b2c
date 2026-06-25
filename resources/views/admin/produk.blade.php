@extends('admin.layout')

@section('title', 'Kelola Produk | ERNA Admin')

@section('custom_css')
<style>
    .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; background: var(--card-bg); padding: 15px 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
    .header-title h2 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--text-dark); margin: 0; }
    .admin-avatar { width: 40px; height: 40px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--card-bg); font-weight: 700; }

    .layout-grid { display: grid; grid-template-columns: 1fr 2.5fr; gap: 30px; }
    .panel-card { background: var(--card-bg); border-radius: 20px; padding: 30px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px rgba(0,0,0,0.02); }

    /* STYLE UNTUK FILTER */
    .filter-section { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid #f4f4f4; }
    .filter-box { display: flex; gap: 10px; align-items: center; }
    .filter-select { padding: 8px 12px; border-radius: 8px; border: 1px solid #ddd; font-family: inherit; font-size: 12px; outline: none; background: #fff; color: #555; min-width: 180px; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); border-radius: 10px; font-family: 'Montserrat', sans-serif; font-size: 13px; background: var(--main-bg); transition: 0.3s; box-sizing: border-box; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--accent); background: var(--card-bg); }

    .btn-submit { background: var(--sidebar-bg); color: var(--card-bg); border: none; padding: 14px; border-radius: 10px; width: 100%; font-weight: 600; font-size: 12px; cursor: pointer; transition: 0.3s; font-family: 'Montserrat', sans-serif; text-transform: uppercase; letter-spacing: 1px; }
    .btn-submit:hover { background: var(--accent); }

    table { width: 100%; border-collapse: collapse; }
    th { text-align: left; padding: 15px; border-bottom: 2px solid var(--border-color); font-size: 11px; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1px; }
    td { padding: 15px; border-bottom: 1px solid var(--border-color); font-size: 13px; font-weight: 500; color: var(--text-dark); }

    .badge-status { padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase; background: #EBF5FF; color: #007BFF; }
    .badge-featured { padding: 5px 12px; border-radius: 20px; font-size: 10px; font-weight: 700; text-transform: uppercase; background: #FFF3CD; color: #D4AF37; margin-left: 5px; }
    .btn-delete { color: #D9534F; background: #FFEAEA; text-decoration: none; font-size: 11px; font-weight: 600; transition: 0.3s; padding: 8px 12px; border-radius: 8px; display: inline-block; font-family: 'Montserrat', sans-serif;}
    .btn-delete:hover { background: #D9534F; color: white; }
    .btn-restore { color: #2ecc71; background: #e9f9ef; text-decoration: none; font-size: 11px; font-weight: 600; padding: 8px 12px; border-radius: 8px; display: inline-block; margin-right: 5px; }
    .btn-restore:hover { background: #2ecc71; color: white; }
    .btn-trash-toggle { 
        padding: 8px 15px; border-radius: 20px; font-size: 11px; font-weight: 700; text-decoration: none; 
        display: flex; align-items: center; gap: 8px; transition: 0.3s;
    }
    .trash-active { background: #FFEAEA; color: #D9534F; border: 1px solid #D9534F; }
    .trash-inactive { background: #f8f9fa; color: #6c757d; border: 1px solid #ddd; }

    @media (max-width: 1000px) { .layout-grid { grid-template-columns: 1fr; } }
</style>
@endsection

@section('content')
<header class="top-header">
    <div class="header-title"><h2>Daftar Produk Katalog</h2></div>
    <div class="admin-avatar">AD</div>
</header>

<section class="layout-grid">
    <div class="panel-card" style="height: fit-content;">
        <h3 style="margin-top: 0; margin-bottom: 20px; font-family:'Playfair Display'; color: var(--text-dark);">Tambah Produk</h3>
        
        <form action="{{ url('/admin/produk/simpan') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" placeholder="Misal: Kemeja Vintage" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->name }}">{{ $c->name }}</option>
                    @endforeach
                    <option value="Flash Sale">Flash Sale (Promo)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp)</label>
                <input type="number" name="harga" placeholder="Contoh: 50000" required>
            </div>
            <div class="form-group">
                <label>Stok Tersedia</label>
                <input type="number" name="stok" placeholder="Jumlah stok" required>
            </div>
            <div class="form-group">
                <label>Deskripsi Produk</label>
                <textarea name="deskripsi" rows="4" placeholder="Jelaskan kondisi baju, minus, ukuran, dll..." style="resize: vertical;" required></textarea>
            </div>
            
            <div class="form-group">
                <label>Foto Utama</label>
                <input type="file" name="gambar" accept=".jpg, .jpeg, .png, .webp" style="padding: 9px; background: var(--main-bg); cursor: pointer;" required>
            </div>
            
            <div class="form-group">
                <label>Foto Tambahan (Bisa pilih lebih dari 1)</label>
                <input type="file" name="gambar_tambahan[]" multiple accept=".jpg, .jpeg, .png, .webp" style="padding: 9px; background: var(--main-bg); cursor: pointer;">
            </div>

            <!-- BAGIAN VARIAN PRODUK -->
            <div class="form-group" style="background: #fdfdfd; padding: 15px; border-radius: 10px; border: 1px solid #eee;">
                <label style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Varian Produk (Warna / Ukuran)</span>
                    <button type="button" id="btn-add-variant" style="background: var(--accent); color: white; border: none; padding: 5px 10px; border-radius: 5px; font-size: 10px; cursor: pointer;">+ Tambah Varian</button>
                </label>
                <p style="font-size: 10px; color: #888; margin-top: -5px; margin-bottom: 15px;">Kosongkan jika produk tidak memiliki varian.</p>
                
                <div id="variant-container">
                    <!-- Variant rows akan ditambahkan ke sini oleh JS -->
                </div>
            </div>
            
            <div class="form-group" style="display: flex; align-items: flex-start; gap: 12px; background: #fafafa; padding: 15px; border-radius: 10px; border: 1px dashed #dcdcdc; margin-bottom: 25px;">
                <input type="checkbox" name="is_featured" value="1" id="is_featured" style="width: auto; margin-top: 3px; cursor: pointer; transform: scale(1.2);">
                <label for="is_featured" style="margin: 0; font-size: 13px; text-transform: none; color: var(--text-dark); cursor: pointer; line-height: 1.4;">
                    <strong>Jadikan "Koleksi Terbaru" di Beranda</strong> <br>
                </label>
            </div>

            <button type="submit" class="btn-submit">Simpan Produk</button>
        </form>
    </div>

    <div class="panel-card">
        <div class="filter-section">
            <h3 style="margin: 0; font-family:'Playfair Display'; color: var(--text-dark);">Data Produk</h3>
            
            <div class="filter-box">
                <a href="{{ url('/admin/produk' . (request('show_deleted') == 'true' ? '' : '?show_deleted=true')) }}" class="btn-trash-toggle {{ request('show_deleted') == 'true' ? 'trash-active' : 'trash-inactive' }}">
                    <i class="fas fa-trash-alt"></i> 
                    {{ request('show_deleted') == 'true' ? 'Lihat Produk Aktif' : 'Tempat Sampah (' . $trashedCount . ')' }}
                </a>
                
                <form action="{{ url('/admin/produk') }}" method="GET" class="filter-box">
                    @if(request('show_deleted')) <input type="hidden" name="show_deleted" value="true"> @endif
                    <label style="font-size: 11px; font-weight: 700; color: #888; text-transform: uppercase;">Filter:</label>
                    <select name="filter_kategori" class="filter-select" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->name }}" {{ request('filter_kategori') == $cat->name ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                        <option value="Flash Sale" {{ request('filter_kategori') == 'Flash Sale' ? 'selected' : '' }}>Flash Sale</option>
                    </select>
                </form>
            </div>
        </div>

        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr>
                            <td style="font-weight: 700; color: var(--accent);">{{ $p->nama_produk }}</td>
                            <td style="color: var(--text-muted); text-transform: uppercase; font-size: 10px; font-weight: 700;">{{ $p->kategori }}</td>
                            <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                            <td>{{ $p->stok }} pcs</td>
                            <td>
                                <span class="badge-status">{{ $p->status ?? 'Tersedia' }}</span>
                                @if($p->is_featured)
                                    <br><span class="badge-featured" style="display: inline-block; margin-top: 5px; margin-left: 0;"><i class="fas fa-star"></i> Beranda</span>
                                @endif
                            </td>
                            <td style="white-space: nowrap;">
                                @if(request('show_deleted') == 'true')
                                    <a href="{{ url('/admin/produk/restore/'.$p->id) }}" class="btn-restore">
                                        <i class="fas fa-undo"></i> Pulihkan
                                    </a>
                                    <a href="{{ url('/admin/produk/hapus-permanen/'.$p->id) }}" class="btn-delete" onclick="return confirm('Hapus permanen produk ini? Data tidak bisa dikembalikan!')">
                                        <i class="fas fa-times-circle"></i> Hapus Permanen
                                    </a>
                                @else
                                    <a href="{{ url('/admin/produk/edit/'.$p->id) }}" style="color: #007BFF; background: #EBF5FF; text-decoration: none; font-size: 11px; font-weight: 600; padding: 8px 12px; border-radius: 8px; display: inline-block; margin-right: 5px;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ url('/admin/produk/hapus/'.$p->id) }}" class="btn-delete" onclick="confirmDelete(event, '{{ $p->nama_produk }}')">
                                        <i class="fas fa-trash-alt"></i> Hapus
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Tidak ada produk dalam kategori ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    document.getElementById('btn-add-variant').addEventListener('click', function() {
        const container = document.getElementById('variant-container');
        const row = document.createElement('div');
        row.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px; align-items: center;';
        row.innerHTML = `
            <input type="text" name="variant_warna[]" placeholder="Warna (Misal: Merah)" style="padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; flex: 1;">
            <input type="text" name="variant_ukuran[]" placeholder="Ukuran (Misal: XL)" style="padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; flex: 1;">
            <input type="number" name="variant_stok[]" placeholder="Stok Varian" required style="padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; width: 80px;">
            <button type="button" onclick="this.parentElement.remove()" style="background: #FFEAEA; color: #D9534F; border: none; padding: 8px; border-radius: 6px; cursor: pointer;"><i class="fas fa-times"></i></button>
        `;
        container.appendChild(row);
    });
</script>
@endsection