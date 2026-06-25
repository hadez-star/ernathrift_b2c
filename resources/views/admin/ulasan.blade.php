@extends('admin.layout')

@section('title', 'Manajemen Ulasan | ERNA Admin')

@section('custom_css')
<style>
    :root { --bg-body: #F4F7F6; --text-dark: #2C2623; --text-muted: #888888; --white: #ffffff; --border: #EFEBE4; }
    body { background-color: var(--bg-body) !important; }

    .top-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; background: var(--white); padding: 15px 30px; border-radius: 15px; box-shadow: 0 5px 20px rgba(0,0,0,0.02); }
    .header-title h2 { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: var(--text-dark); margin: 0; }
    .admin-avatar { width: 40px; height: 40px; background: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--white); font-weight: 700; }

    .review-card { background: white; border-radius: 15px; padding: 25px; margin-bottom: 25px; border: 1px solid var(--border); box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: 0.3s; }
    .review-card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.05); }

    .stars { color: #F1C40F; margin-bottom: 10px; }
    .user-info { display: flex; align-items: center; gap: 15px; margin-bottom: 15px; }
    .user-avatar { width: 35px; height: 35px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #555; font-size: 12px; }
    .user-name { font-weight: 700; color: var(--text-dark); font-size: 14px; }
    .review-date { font-size: 11px; color: var(--text-muted); }

    .product-box { background: var(--bg-body); padding: 12px 15px; border-radius: 10px; display: flex; align-items: center; gap: 12px; margin-bottom: 15px; }
    .product-img { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; }
    .product-name { font-weight: 600; font-size: 12px; color: var(--text-dark); }

    .komentar-text { font-size: 13px; color: #444; line-height: 1.6; margin-bottom: 20px; font-style: italic; background: #fffbf0; padding: 15px; border-radius: 8px; border-left: 3px solid #F1C40F; }

    .admin-reply-box { margin-top: 20px; padding-top: 20px; border-top: 1px dashed var(--border); }
    .reply-label { font-size: 11px; font-weight: 700; color: var(--accent); text-transform: uppercase; margin-bottom: 10px; display: block; }
    .reply-content { font-size: 13px; color: #666; background: #f9f9f9; padding: 15px; border-radius: 8px; border-left: 3px solid var(--accent); }

    .reply-form textarea { width: 100%; border: 1px solid var(--border); border-radius: 10px; padding: 15px; font-family: 'Montserrat', sans-serif; font-size: 13px; min-height: 100px; outline: none; transition: 0.3s; margin-bottom: 10px; }
    .reply-form textarea:focus { border-color: var(--accent); box-shadow: 0 0 10px rgba(176, 137, 104, 0.1); }
    .btn-reply { background: var(--text-dark); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 12px; cursor: pointer; transition: 0.3s; }
    .btn-reply:hover { background: #4A403A; }

    .filter-pills { display: flex; gap: 10px; margin-bottom: 30px; overflow-x: auto; padding-bottom: 5px; }
    .pill { padding: 8px 20px; background: white; border: 1px solid var(--border); border-radius: 30px; font-size: 11px; font-weight: 600; color: var(--text-muted); cursor: pointer; transition: 0.3s; white-space: nowrap; }
    .pill.active { background: var(--text-dark); color: white; border-color: var(--text-dark); }
</style>
@endsection

@section('content')
<header class="top-header">
    <div class="header-title"><h2>Manajemen Ulasan Pelanggan</h2></div>
    <div class="admin-avatar">AD</div>
</header>

<div class="filter-pills">
    <a href="{{ url('/admin/ulasan') }}" class="pill {{ !request('filter') && !request('rating') ? 'active' : '' }}" style="text-decoration:none;">Semua Ulasan</a>
    <a href="{{ url('/admin/ulasan?filter=belum_dibalas') }}" class="pill {{ request('filter') == 'belum_dibalas' ? 'active' : '' }}" style="text-decoration:none;">Belum Dibalas</a>
    <a href="{{ url('/admin/ulasan?filter=sudah_dibalas') }}" class="pill {{ request('filter') == 'sudah_dibalas' ? 'active' : '' }}" style="text-decoration:none;">Sudah Dibalas</a>
    <a href="{{ url('/admin/ulasan?rating=5') }}" class="pill {{ request('rating') == 5 ? 'active' : '' }}" style="text-decoration:none;">Rating ★★★★★</a>
    <a href="{{ url('/admin/ulasan?rating=1') }}" class="pill {{ request('rating') == 1 ? 'active' : '' }}" style="text-decoration:none;">Rating ★</a>
</div>

<div class="reviews-list">
    @forelse($reviews as $r)
    <div class="review-card">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr($r->user->name, 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ $r->user->name }}</div>
                    <div class="review-date">{{ $r->created_at->format('d M Y, H:i') }} | Invoice: #{{ $r->invoice }}</div>
                </div>
            </div>
            <div class="stars">
                @for($i=1; $i<=5; $i++)
                    <i class="{{ $i <= $r->rating ? 'fas' : 'far' }} fa-star"></i>
                @endfor
            </div>
        </div>

        <div class="product-box">
            @if($r->product)
                <img src="{{ asset($r->product->gambar) }}" class="product-img">
                <div class="product-name">{{ $r->product->nama_produk }}</div>
            @else
                <div class="product-name" style="color: #e74c3c;">Produk telah dihapus</div>
            @endif
        </div>

        <div class="komentar-text">
            "{{ $r->komentar }}"
        </div>

        @if($r->foto)
            <div style="margin-bottom: 20px;">
                <span class="reply-label">FOTO DARI PELANGGAN:</span><br>
                <img src="{{ asset($r->foto) }}" style="width: 150px; height: 150px; object-fit: cover; border-radius: 12px; border: 1px solid var(--border); cursor: pointer;" onclick="window.open(this.src)">
            </div>
        @endif

        @if($r->balasan_admin)
            <div class="admin-reply-box">
                <span class="reply-label">Balasan Admin:</span>
                <div class="reply-content">
                    {{ $r->balasan_admin }}
                </div>
                <div style="margin-top: 15px;">
                    <button class="btn-reply" onclick="toggleEdit('{{ $r->id }}')" style="background: var(--accent); color: var(--white);">Edit Balasan</button>
                </div>
            </div>
        @endif

        <div class="reply-form" id="form-{{ $r->id }}" style="{{ $r->balasan_admin ? 'display:none;' : '' }}">
            <span class="reply-label">{{ $r->balasan_admin ? 'Edit Balasan:' : 'Balas Ulasan Ini:' }}</span>
            <form action="{{ url('/admin/ulasan/balas') }}" method="POST">
                @csrf
                <input type="hidden" name="review_id" value="{{ $r->id }}">
                <textarea name="balasan" placeholder="Tulis balasan Anda di sini... (Contoh: Terima kasih banyak atas kepercayaannya Kak! Kami tunggu orderan berikutnya ya!)" required>{{ $r->balasan_admin }}</textarea>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-reply">Simpan Balasan</button>
                    @if($r->balasan_admin)
                        <button type="button" class="btn-reply" style="background: #eee; color: #555;" onclick="toggleEdit('{{ $r->id }}')">Batal</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
    @empty
    <div style="text-align: center; padding: 50px; background: white; border-radius: 15px; border: 1px dashed var(--border);">
        <i class="far fa-comment-dots" style="font-size: 40px; color: var(--border); margin-bottom: 20px; display: block;"></i>
        <p style="color: var(--text-muted);">Belum ada ulasan yang masuk dari pelanggan.</p>
    </div>
    @endforelse
</div>

@endsection

@section('scripts')
<script>
    function toggleEdit(id) {
        const form = document.getElementById('form-' + id);
        if(form.style.display === 'none') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }

    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonColor: '#B08968'
    });
    @endif
</script>
@endsection