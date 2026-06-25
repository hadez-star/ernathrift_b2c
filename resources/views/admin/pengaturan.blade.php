@extends('admin.layout')

@section('title', 'Pengaturan Web | ERNA Admin')

@section('custom_css')
<style>
    :root { --bg-body: #F4F7F6; --text-dark: #2C2623; --text-muted: #888888; --white: #ffffff; --border: #EFEBE4; }
    body { background-color: var(--bg-body) !important; }

    .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; background: var(--white); padding: 15px 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
    .header-title h2 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--text-dark); margin-bottom: 5px; }
    .admin-avatar { width: 40px; height: 40px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--white); font-weight: 700; }

    .panel-card { background: var(--white); border-radius: 20px; padding: 40px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.02); max-width: 800px; }
    .panel-title { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; margin-bottom: 30px; border-bottom: 1px solid var(--border); padding-bottom: 15px; color: var(--text-dark); }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 12px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; }
    .form-group input, .form-group textarea { width: 100%; padding: 12px 15px; border: 1px solid var(--border); border-radius: 10px; font-family: 'Montserrat', sans-serif; outline: none; background: var(--bg-body); transition: 0.3s; font-size: 13px; color: var(--text-dark); }
    .form-group input:focus, .form-group textarea:focus { border-color: var(--accent); background: var(--white); }
    .form-group textarea { resize: vertical; min-height: 80px; }

    .btn-submit { background: var(--dark-bg, #2C2623); color: var(--white); padding: 15px 30px; border-radius: 10px; font-weight: 600; cursor: pointer; border: none; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px; font-size: 12px; float: right; margin-top: 10px; font-family: 'Montserrat', sans-serif; }
    .btn-submit:hover { background: var(--accent); }

    /* KUSTOMISASI SWEETALERT AGAR LEBIH PREMIUM */
    .swal2-popup {
        border-radius: 20px !important;
        border: 1px solid #EFEBE4 !important;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1) !important;
        padding: 2em !important;
    }
    .swal2-title {
        font-family: 'Playfair Display', serif !important;
        color: #2C2623 !important;
        font-size: 28px !important;
    }
    .swal2-html-container {
        font-family: 'Montserrat', sans-serif !important;
        color: #888888 !important;
        font-size: 14px !important;
    }
    .swal2-confirm {
        border-radius: 10px !important;
        font-family: 'Montserrat', sans-serif !important;
        font-weight: 600 !important;
        padding: 12px 35px !important;
        text-transform: uppercase !important;
        letter-spacing: 1px !important;
    }
</style>
@endsection

@section('content')
<header class="top-header">
    <div class="header-title">
        <h2>Pengaturan Web</h2>
        <p style="font-size: 12px; color: var(--text-muted); margin-top: 5px; margin-bottom: 0;">Ubah informasi dasar toko yang akan tampil di halaman depan pengunjung.</p>
    </div>
    <div class="admin-avatar">AD</div>
</header>

<div class="panel-card">
    <div class="panel-title"><i class="fas fa-store" style="color: var(--accent); margin-right: 10px;"></i> Profil Toko & Kontak</div>
    
    <form action="/admin/pengaturan/simpan" method="POST">
        @csrf
        <div class="form-grid">
            <div class="form-group">
                <label>Nama Toko</label>
                <input type="text" name="nama_toko" value="{{ $setting->nama_toko ?? 'ERNA THRIFTING' }}" required>
            </div>
            <div class="form-group">
                <label>Email Kontak</label>
                <input type="email" name="email" value="{{ $setting->email ?? 'hello@ernathrifting.com' }}" required>
            </div>
            <div class="form-group">
                <label>Nomor WhatsApp (Untuk Footer)</label>
                <input type="text" name="whatsapp" value="{{ $setting->whatsapp ?? '6281234567890' }}" required>
            </div>
            <div class="form-group">
                <label>Alamat Toko</label>
                <input type="text" name="alamat" value="{{ $setting->alamat ?? 'Pontianak, Indonesia' }}" required>
            </div>
        </div>

        <div class="form-group">
            <label>Deskripsi Singkat Toko (Footer)</label>
            <textarea rows="3" name="deskripsi" required>{{ $setting->deskripsi ?? 'Menghadirkan koleksi pakaian bekas berkualitas premium yang dikurasi dengan ketat. Kami percaya bahwa gaya yang memukau tidak harus merusak bumi.' }}</textarea>
        </div>

        <button type="submit" class="btn-submit">Simpan Perubahan</button>
        <div style="clear: both;"></div> 
    </form>
</div>
@endsection

@section('scripts')
    @if(session('success'))
    <script>
        Swal.fire({
            title: 'Tersimpan!',
            text: "{{ session('success') }}",
            icon: 'success',
            iconColor: '#B08968',
            background: '#ffffff',
            backdrop: `rgba(44, 38, 35, 0.6)`,
            confirmButtonColor: '#2C2623',
            confirmButtonText: 'Tutup'
        });
    </script>
    @endif
@endsection