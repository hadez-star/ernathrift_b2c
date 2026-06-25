@extends('admin.layout')

@section('title', 'Kategori & Filter | ERNA Admin')

@section('custom_css')
<style>
    .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; background: var(--card-bg); padding: 15px 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
    .header-title h2 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--text-dark); margin: 0; }
    .admin-avatar { width: 40px; height: 40px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--card-bg); font-weight: 700; }
    
    .layout-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 30px; }
    .panel-card { background: var(--card-bg); border-radius: 20px; padding: 30px; border: 1px solid var(--border-color); box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
    
    .form-group label { display: block; font-size: 11px; font-weight: 600; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; }
    .form-group input { width: 100%; padding: 12px 15px; border: 1px solid var(--border-color); border-radius: 10px; margin-bottom: 20px; font-family: 'Montserrat', sans-serif; font-size: 13px; background: var(--main-bg); transition: 0.3s; box-sizing: border-box; }
    .form-group input:focus { outline: none; border-color: var(--accent); background: var(--card-bg); }
    
    .btn-submit { background: var(--sidebar-bg); color: var(--card-bg); border: none; padding: 14px; border-radius: 10px; width: 100%; font-weight: 600; font-size: 12px; cursor: pointer; transition: 0.3s; font-family: 'Montserrat', sans-serif; text-transform: uppercase; letter-spacing: 1px; }
    .btn-submit:hover { background: var(--accent); }
    
    table { width: 100%; border-collapse: collapse; } 
    th { text-align: left; padding: 15px; border-bottom: 2px solid var(--border-color); font-size: 11px; text-transform: uppercase; color: var(--text-muted); letter-spacing: 1px; } 
    td { padding: 15px; border-bottom: 1px solid var(--border-color); font-size: 13px; font-weight: 500; color: var(--text-dark); }
    
    .btn-delete { color: #D9534F; background: #FFEAEA; text-decoration: none; font-size: 11px; font-weight: 600; transition: 0.3s; padding: 8px 12px; border-radius: 8px; display: inline-block; font-family: 'Montserrat', sans-serif;}
    .btn-delete:hover { background: #D9534F; color: white; }
</style>
@endsection

@section('content')
<header class="top-header">
    <div class="header-title"><h2>Kategori & Filter</h2></div>
    <div class="admin-avatar">AD</div>
</header>

<section class="layout-grid">
    <div class="panel-card" style="height: fit-content;">
        <h3 style="margin-top: 0; margin-bottom: 20px; font-family:'Playfair Display'; color: var(--text-dark);">Tambah Kategori</h3>
        
        <form action="{{ url('/admin/kategori/simpan') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="name" placeholder="Contoh: Baju Pria" required>
            </div>
            <button type="submit" class="btn-submit">Simpan Kategori</button>
        </form>
    </div>

    <div class="panel-card">
        <h3 style="margin-top: 0; margin-bottom: 20px; font-family:'Playfair Display'; color: var(--text-dark);">Daftar Kategori</h3>
        <div style="overflow-x: auto;">
            <table>
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Slug</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td style="font-weight: 700; color: var(--accent);">{{ $cat->name }}</td>
                            <td style="color: var(--text-muted);">{{ $cat->slug }}</td>
                            <td>
                                <a href="{{ url('/admin/kategori/hapus/'.$cat->id) }}" class="btn-delete" onclick="confirmDelete(event, '{{ $cat->name }}')">
                                    <i class="fas fa-trash-alt"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 40px; color: var(--text-muted);">Belum ada kategori yang ditambahkan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection