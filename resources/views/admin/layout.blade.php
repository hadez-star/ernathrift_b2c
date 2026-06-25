<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin | ERNA Thrifting')</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;900&family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --sidebar-bg: #231F1E;
            --main-bg: #F4F1EA; 
            --accent: #B08968;
            --text-muted: #888888;
            --text-dark: #2C2623;
            --card-bg: #FFFFFF;
        }
        
        body {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            background-color: var(--main-bg) !important;
            display: flex;
            min-height: 100vh;
            color: var(--text-dark);
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 250px;
            background-color: var(--sidebar-bg);
            color: #AFA9A4;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
        }
        
        /* Modifikasi custom scrollbar untuk sidebar agar tidak kaku */
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        
        .sidebar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--accent);
            text-align: center;
            padding: 30px 20px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .nav-group {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #666;
            padding: 20px 20px 10px;
            font-weight: 700;
        }
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #AFA9A4;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: 0.3s;
            margin: 2px 15px;
            border-radius: 8px;
        }
        .nav-link i {
            width: 25px;
            font-size: 15px;
        }
        .nav-link:hover {
            background-color: rgba(176, 137, 104, 0.1);
            color: var(--accent);
        }
        .nav-link.active {
            background-color: rgba(176, 137, 104, 0.2);
            color: var(--accent);
            font-weight: 600;
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 40px;
            background-color: var(--main-bg);
            min-height: 100vh;
            box-sizing: border-box;
        }
        
        .panel-card, .card, .bg-white {
            background-color: var(--card-bg) !important;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.04);
            border: 1px solid #EFEBE4;
        }

        /* CSS Khusus Pop-Up Swal Premium */
        .premium-swal-popup { border-radius: 20px !important; border: 1px solid #EFEBE4; }
    </style>
    @yield('custom_css')
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">ERNA ADMIN</div>
        
        <div class="nav-group">Utama</div>
        <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        
        <div class="nav-group">Manajemen Toko</div>
        <a href="{{ url('/admin/produk') }}" class="nav-link {{ request()->is('admin/produk') ? 'active' : '' }}">
            <i class="fas fa-tshirt"></i> Kelola Produk
        </a>
        <a href="{{ url('/admin/pesanan') }}" class="nav-link {{ request()->is('admin/pesanan') ? 'active' : '' }}">
            <i class="fas fa-box-open"></i> Kelola Pesanan
        </a>
        
        <!-- MENU TAMBAHAN BARU: Kelola Ulasan -->
        <a href="{{ url('/admin/ulasan') }}" class="nav-link {{ request()->is('admin/ulasan') ? 'active' : '' }}">
            <i class="fas fa-star"></i> Kelola Ulasan
        </a>
        
        <a href="{{ url('/admin/kategori') }}" class="nav-link {{ request()->is('admin/kategori') ? 'active' : '' }}">
            <i class="fas fa-tags"></i> Kategori & Filter
        </a>
        <a href="{{ url('/admin/pelanggan') }}" class="nav-link {{ request()->is('admin/pelanggan') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Data Pelanggan
        </a>
        
        <div class="nav-group">Pemasaran</div>
        <a href="{{ url('/admin/flash-sale') }}" class="nav-link {{ request()->is('admin/flash-sale') ? 'active' : '' }}">
            <i class="fas fa-bolt"></i> Flash Sale
        </a>
        <a href="{{ url('/admin/voucher') }}" class="nav-link {{ request()->is('admin/voucher') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt"></i> Kode Voucher
        </a>
        
        <div class="nav-group">Pengaturan</div>
        <a href="{{ url('/admin/pengaturan') }}" class="nav-link {{ request()->is('admin/pengaturan') ? 'active' : '' }}">
            <i class="fas fa-cog"></i> Pengaturan Web
        </a>
        <a href="{{ url('/') }}" class="nav-link" target="_blank">
            <i class="fas fa-eye"></i> Lihat Toko
        </a>
        
        <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); confirmLogout();" style="color: #e74c3c; margin-top: auto; margin-bottom: 20px;">
            <i class="fas fa-sign-out-alt" style="color: #e74c3c;"></i> Keluar
        </a>
    </div>

    <div class="main-content">
        @yield('content')
    </div>

    <script>
        // Notifikasi Sukses
        @if(session('success'))
            Swal.fire({
                width: '380px', showConfirmButton: false, timer: 3000, backdrop: `rgba(0,0,0,0.4)`,
                html: `
                    <div style="text-align: center; padding: 10px 0;">
                        <div style="background-color: #FDFBF7; border: 2px solid #EFEBE4; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px;">
                            <i class="fas fa-check" style="color: #B08968; font-size: 35px;"></i>
                        </div>
                        <h2 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #2C2623; margin-bottom: 10px; font-weight: 700;">Berhasil!</h2>
                        <p style="font-family: 'Montserrat', sans-serif; font-size: 13px; color: #888888; line-height: 1.6; margin-bottom: 25px;">{{ session('success') }}</p>
                        <button onclick="Swal.close()" style="width: 100%; background: #231F1E; color: white; padding: 14px; border: none; border-radius: 10px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 12px; text-transform: uppercase; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#B08968'" onmouseout="this.style.background='#231F1E'">Lanjutkan</button>
                    </div>
                `,
                customClass: { popup: 'premium-swal-popup' }
            });
        @endif

        // Notifikasi Error
        @if(session('error'))
            Swal.fire({
                width: '380px', showConfirmButton: false, timer: 4000, backdrop: `rgba(0,0,0,0.4)`,
                html: `
                    <div style="text-align: center; padding: 10px 0;">
                        <div style="background-color: #FFF5F5; border: 2px solid #FFEBEB; width: 80px; height: 80px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin: 0 auto 20px;">
                            <i class="fas fa-exclamation" style="color: #D9534F; font-size: 35px;"></i>
                        </div>
                        <h2 style="font-family: 'Playfair Display', serif; font-size: 24px; color: #2C2623; margin-bottom: 10px; font-weight: 700;">Oops!</h2>
                        <p style="font-family: 'Montserrat', sans-serif; font-size: 13px; color: #888888; line-height: 1.6; margin-bottom: 25px;">{{ session('error') }}</p>
                        <button onclick="Swal.close()" style="width: 100%; background: #D9534F; color: white; padding: 14px; border: none; border-radius: 10px; font-family: 'Montserrat', sans-serif; font-weight: 600; font-size: 12px; text-transform: uppercase; cursor: pointer; transition: 0.3s;">Tutup</button>
                    </div>
                `,
                customClass: { popup: 'premium-swal-popup' }
            });
        @endif

        // FUNGSI GLOBAL KONFIRMASI HAPUS (PREMIUM STYLE)
        function confirmDelete(event, itemName) {
            event.preventDefault(); // Mencegah link langsung berpindah halaman
            const url = event.currentTarget.getAttribute('href');

            Swal.fire({
                title: `<div style="font-family: 'Playfair Display', serif; font-size: 26px; color: #2C2623;">Hapus Data?</div>`,
                html: `<p style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #888888;">Apakah Anda yakin ingin menghapus <b>${itemName}</b>? Tindakan ini tidak dapat dibatalkan.</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D9534F',
                cancelButtonColor: '#EFEBE4',
                confirmButtonText: '<span style="font-family:\'Montserrat\', sans-serif; font-weight:600; font-size:13px; letter-spacing:1px;">YA, HAPUS!</span>',
                cancelButtonText: '<span style="font-family:\'Montserrat\', sans-serif; font-weight:600; font-size:13px; color:#2C2623; letter-spacing:1px;">BATAL</span>',
                customClass: { popup: 'premium-swal-popup' },
                reverseButtons: true // Membalik posisi tombol agar 'Batal' di kiri
            }).then((result) => {
                if (result.isConfirmed) {
                    // Tambahan efek loading sebelum halaman berpindah
                    Swal.fire({
                        title: 'Memproses...',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    window.location.href = url; // Arahkan ke URL hapus jika dikonfirmasi
                }
            });
        }

        // FUNGSI LOGOUT DENGAN KONFIRMASI (PREMIUM STYLE)
        function confirmLogout() {
            Swal.fire({
                title: `<div style="font-family: 'Playfair Display', serif; font-size: 26px; color: #2C2623;">Keluar Portal?</div>`,
                html: `<p style="font-family: 'Montserrat', sans-serif; font-size: 14px; color: #888888;">Apakah Anda yakin ingin keluar dari panel Administrator?</p>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D9534F',
                cancelButtonColor: '#EFEBE4',
                confirmButtonText: '<span style="font-family:\'Montserrat\', sans-serif; font-weight:600; font-size:13px; letter-spacing:1px;">YA, KELUAR</span>',
                cancelButtonText: '<span style="font-family:\'Montserrat\', sans-serif; font-weight:600; font-size:13px; color:#2C2623; letter-spacing:1px;">BATAL</span>',
                customClass: { popup: 'premium-swal-popup' },
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Keluar...',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    window.location.href = "{{ route('logout') }}";
                }
            });
        }
    </script>
    
    @yield('scripts')
</body>
</html>