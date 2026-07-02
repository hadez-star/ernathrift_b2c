@extends('admin.layout')

@section('title', 'Edit Produk | ERNA Admin')

@section('custom_css')
<style>
    .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; background: var(--card-bg); padding: 15px 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
    .header-title h2 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--text-dark); margin: 0; }
    .admin-avatar { width: 40px; height: 40px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--card-bg); font-weight: 700; }

    .panel-card { background: var(--card-bg); border-radius: 20px; padding: 35px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px rgba(0,0,0,0.02); max-width: 800px; margin: 0 auto; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; }
    .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); border-radius: 10px; font-family: 'Montserrat', sans-serif; font-size: 13px; background: var(--main-bg); transition: 0.3s; box-sizing: border-box; }
    .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--accent); background: var(--card-bg); }

    .btn-submit { background: #274D5A; color: var(--card-bg); border: none; padding: 15px; border-radius: 10px; width: 100%; font-weight: 600; font-size: 12px; cursor: pointer; transition: 0.3s; font-family: 'Montserrat', sans-serif; text-transform: uppercase; letter-spacing: 1px; margin-top: 10px; }
    .btn-submit:hover { background: #1a333d; }
    .btn-back { display: inline-flex; align-items: center; gap: 8px; margin-bottom: 25px; color: var(--text-muted); text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.3s; }
    .btn-back:hover { color: var(--accent); }
</style>
@endsection

@section('content')
<header class="top-header">
    <div class="header-title"><h2>Edit Data Produk</h2></div>
    <div class="admin-avatar">AD</div>
</header>

<div style="max-width: 800px; margin: 0 auto;">
    <a href="{{ url('/admin/produk') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Produk</a>

    <div class="panel-card">
        <form action="{{ url('/admin/produk/update/'.$product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" value="{{ $product->nama_produk }}" required>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->name }}" {{ $product->kategori == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                        <option value="Flash Sale" {{ $product->kategori == 'Flash Sale' ? 'selected' : '' }}>Flash Sale (Promo)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Harga (Rp)</label>
                    <input type="number" name="harga" value="{{ $product->harga }}" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Stok Tersedia</label>
                <input type="number" name="stok" value="{{ $product->stok }}" required>
            </div>
            
            <div class="form-group">
                <label>Deskripsi Produk</label>
                <textarea name="deskripsi" rows="5" style="resize: vertical;" required>{{ $product->deskripsi }}</textarea>
            </div>
            
            <div class="form-group" style="background: var(--main-bg); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color);">
                <label>Ganti Foto Utama</label>
                @if($product->gambar)
                    <div style="margin-bottom: 15px; display: flex; align-items: center; gap: 15px;">
                        <img src="{{ asset($product->gambar) }}" alt="Foto Lama" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 2px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                        <span style="font-size: 11px; color: #888;">Foto saat ini</span>
                    </div>
                @endif
                <input type="file" name="gambar" accept=".jpg, .jpeg, .png, .webp" style="padding: 10px; background: #fff; cursor: pointer;">
                <small style="color: #e74c3c; font-size: 10px; display: block; margin-top: 8px; font-weight: 600;"><i class="fas fa-info-circle"></i> Biarkan kosong jika tidak ingin mengganti foto yang sudah ada.</small>
            </div>

            <div class="form-group" style="background: var(--main-bg); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color);">
                <label>Tambah Foto Galeri</label>
                @if($product->images && $product->images->count() > 0)
                    <div style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px;">
                        @foreach($product->images as $img)
                            <img src="{{ asset($img->image_path) }}" alt="Foto Tambahan" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; border: 1px solid #ddd;">
                        @endforeach
                    </div>
                @endif
                <input type="file" name="gambar_tambahan[]" multiple accept=".jpg, .jpeg, .png, .webp" style="padding: 10px; background: #fff; cursor: pointer;">
                <small style="color: #e74c3c; font-size: 10px; display: block; margin-top: 8px; font-weight: 600;"><i class="fas fa-info-circle"></i> Biarkan kosong jika tidak ingin menambah foto galeri baru.</small>
            </div>
            
            <!-- BAGIAN VARIAN PRODUK -->
            <div class="form-group" style="background: #fdfdfd; padding: 15px; border-radius: 10px; border: 1px solid #eee;">
                <label style="display: flex; justify-content: space-between; align-items: center;">
                    <span>Varian Produk (Warna / Ukuran)</span>
                    <button type="button" id="btn-add-variant" style="background: var(--accent); color: white; border: none; padding: 5px 10px; border-radius: 5px; font-size: 10px; cursor: pointer;">+ Tambah Varian</button>
                </label>
                
                <div id="variant-container">
                    @foreach($product->variants as $var)
                    <div style="display: flex; gap: 10px; margin-bottom: 10px; align-items: center;">
                        <input type="hidden" name="variant_id[]" value="{{ $var->id }}">
                        <input type="text" name="variant_warna[]" value="{{ $var->warna }}" placeholder="Warna" style="padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; flex: 1;">
                        <input type="text" name="variant_ukuran[]" value="{{ $var->ukuran }}" placeholder="Ukuran" style="padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; flex: 1;">
                        <input type="number" name="variant_stok[]" value="{{ $var->stok }}" placeholder="Stok Varian" required style="padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; width: 80px;">
                        <label style="font-size: 10px; color:#D9534F; cursor: pointer;"><input type="checkbox" name="delete_variant[]" value="{{ $var->id }}"> Hapus</label>
                    </div>
                    @endforeach
                </div>
            </div>

            
            <div class="form-group" style="display: flex; align-items: flex-start; gap: 12px; background: #fafafa; padding: 15px; border-radius: 10px; border: 1px dashed #dcdcdc; margin-bottom: 25px;">
                <input type="checkbox" name="is_featured" value="1" id="is_featured" {{ $product->is_featured ? 'checked' : '' }} style="width: auto; margin-top: 3px; cursor: pointer; transform: scale(1.2);">
                <label for="is_featured" style="margin: 0; font-size: 13px; text-transform: none; color: var(--text-dark); cursor: pointer; line-height: 1.4;">
                    <strong>Jadikan "Koleksi Terbaru" di Beranda</strong>
                </label>
            </div>

            <button type="submit" class="btn-submit"><i class="fas fa-save" style="margin-right: 5px;"></i> Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('btn-add-variant').addEventListener('click', function() {
        const container = document.getElementById('variant-container');
        const row = document.createElement('div');
        row.style.cssText = 'display: flex; gap: 10px; margin-bottom: 10px; align-items: center;';
        row.innerHTML = `
            <input type="hidden" name="variant_id[]" value="new">
            <input type="text" name="variant_warna[]" placeholder="Warna (Misal: Merah)" style="padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; flex: 1;">
            <input type="text" name="variant_ukuran[]" placeholder="Ukuran (Misal: XL)" style="padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; flex: 1;">
            <input type="number" name="variant_stok[]" placeholder="Stok Varian" required style="padding: 8px; border: 1px solid #ddd; border-radius: 6px; font-size: 11px; width: 80px;">
            <button type="button" onclick="this.parentElement.remove()" style="background: #FFEAEA; color: #D9534F; border: none; padding: 8px; border-radius: 6px; cursor: pointer;"><i class="fas fa-times"></i></button>
        `;
        container.appendChild(row);
    });
</script>
@endsection