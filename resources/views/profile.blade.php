@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
    $cartCount = Auth::check() ? \App\Models\Cart::where('user_id', Auth::id())->sum('jumlah') : 0;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Profil Saya' }} | {{ $nama_toko }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* TEMA GELAP (DEFAULT) */
        :root {
            --gold: #D4AF37;
            --gold-hover: #bda038;
            --bg-dark: #0f0f0f;
            --bg-surface: #1a1a1a;
            --text-main: #f5f5f5;
            --text-muted: #888888;
            --border-color: #2a2a2a;
            --red-accent: #e74c3c;
        }

        /* TEMA TERANG (LIGHT MODE) */
        body.light-mode {
            --bg-dark: #f5f5f5;
            --bg-surface: #ffffff;
            --text-main: #111111;
            --text-muted: #666666;
            --border-color: #dddddd;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            background: radial-gradient(circle at top center, #1f1a14 0%, var(--bg-dark) 100%);
            transition: background-color 0.4s ease, color 0.4s ease;
        }
        
        body.light-mode {
            background: radial-gradient(circle at top center, #ffffff 0%, var(--bg-dark) 100%);
        }

        /* --- LAYOUT UTAMA --- */
        .profile-container { max-width: 1000px; margin: 0 auto; padding: 100px 20px 60px; animation: eleganceIn 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; opacity: 0; transform: translateY(30px); }
        @keyframes eleganceIn { to { transform: translateY(0); opacity: 1; } }
        @keyframes flash { from { opacity: 0.4; } to { opacity: 1; } }

        .dashboard-layout { display: grid; grid-template-columns: 320px 1fr; gap: 30px; align-items: flex-start; }

        /* --- SISI KIRI: KARTU PROFIL --- */
        .profile-card {
            background: var(--bg-surface);
            border: 1px solid rgba(212, 175, 55, 0.15);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 25px 50px rgba(0,0,0,0.6), inset 0 0 20px rgba(212, 175, 55, 0.02);
            transition: background-color 0.4s ease, box-shadow 0.4s ease, border-color 0.4s ease;
        }
        body.light-mode .profile-card { box-shadow: 0 25px 50px rgba(0,0,0,0.05), inset 0 0 20px rgba(212, 175, 55, 0.05); }

        .profile-cover {
            height: 120px;
            background: linear-gradient(rgba(15, 15, 15, 0.5), rgba(15, 15, 15, 0.8)), url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop') center/cover;
            position: relative;
        }

        .profile-avatar-wrap {
            width: 110px; height: 110px;
            border-radius: 50%;
            margin: -55px auto 15px;
            border: 4px solid var(--bg-surface);
            background: #222;
            position: relative;
            z-index: 2;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.5);
            transition: border-color 0.4s ease;
        }
        .profile-avatar-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .profile-avatar-wrap .default-avatar { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 40px; color: var(--gold); background: rgba(212, 175, 55, 0.1); }

        .profile-info { padding: 0 20px 20px; text-align: center; }
        .profile-name { font-family: 'Playfair Display', serif; font-size: 24px; color: var(--gold); margin-bottom: 5px; font-weight: 700; text-transform: capitalize; }
        .profile-email { font-size: 12px; color: var(--text-muted); margin-bottom: 20px; transition: color 0.4s ease;}
        
        .badge-vip {
            display: inline-block; padding: 6px 18px;
            background: rgba(212, 175, 55, 0.1);
            border: 1px solid rgba(212, 175, 55, 0.3);
            color: var(--gold); border-radius: 30px;
            font-size: 10px; font-weight: 700; letter-spacing: 1px;
            text-transform: uppercase;
        }
        .badge-vip.reguler { background: rgba(136, 136, 136, 0.05); border-color: rgba(136, 136, 136, 0.2); color: var(--text-muted); }

        .profile-balance {
            border-top: 1px solid rgba(136, 136, 136, 0.1);
            padding: 25px 20px;
            text-align: center;
            background: rgba(0,0,0,0.1);
            transition: border-color 0.4s ease, background 0.4s ease;
        }
        body.light-mode .profile-balance { background: rgba(0,0,0,0.02); }
        .balance-label { font-size: 10px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px; display: block; transition: color 0.4s ease;}
        .balance-amount { font-family: 'Playfair Display', serif; font-size: 26px; color: var(--text-main); font-weight: 700; transition: color 0.4s ease;}

        /* --- SISI KANAN: MENU AKSI (GRID) --- */
        .menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; align-content: start; }

        .action-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 12px; padding: 25px 20px;
            display: flex; align-items: center; gap: 20px;
            text-decoration: none; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative; overflow: hidden; cursor: pointer;
        }

        .action-card::before { content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 100%; background: var(--gold); opacity: 0; transition: 0.3s; }
        .action-card:hover::before { opacity: 1; }

        .action-card:hover {
            transform: translateY(-5px);
            border-color: rgba(212, 175, 55, 0.4);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            background: var(--bg-surface);
        }
        body.light-mode .action-card:hover { box-shadow: 0 15px 30px rgba(0,0,0,0.05); }

        .action-icon {
            width: 50px; height: 50px; border-radius: 12px; flex-shrink: 0;
            background: rgba(212, 175, 55, 0.05); color: var(--gold);
            display: flex; align-items: center; justify-content: center; font-size: 20px;
            border: 1px solid rgba(212, 175, 55, 0.1); transition: 0.4s;
        }

        .action-card:hover .action-icon { background: var(--gold); color: #fff; transform: scale(1.1) rotate(5deg); }
        body.light-mode .action-card:hover .action-icon { color: var(--bg-dark); }

        .action-text h4 { font-size: 14px; color: var(--text-main); margin-bottom: 5px; font-weight: 600; transition: color 0.4s ease;}
        .action-text p { font-size: 11px; color: var(--text-muted); line-height: 1.4; transition: color 0.4s ease;}

        /* Logout Special Style */
        .card-logout { border-color: rgba(231, 76, 60, 0.2); }
        .card-logout::before { background: var(--red-accent); }
        .card-logout .action-icon { color: var(--red-accent); background: rgba(231, 76, 60, 0.05); border-color: rgba(231, 76, 60, 0.1); }
        .card-logout:hover { border-color: rgba(231, 76, 60, 0.4); }
        .card-logout:hover .action-icon { background: var(--red-accent); color: #fff; transform: scale(1.1); }
        .card-logout .action-text h4 { color: var(--red-accent); }

        /* --- TOAST & CUSTOM SWEETALERT PREMIUM --- */
        .ecommerce-toast {
            border-radius: 12px !important;
            padding: 10px 20px !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.8) !important;
            font-family: 'Montserrat', sans-serif !important;
            background: var(--bg-surface) !important;
            color: var(--text-main) !important;
            border: 1px solid var(--border-color) !important;
        }
        body.light-mode .ecommerce-toast { box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
        .ecommerce-toast.swal2-icon-success { border-left: 5px solid var(--gold) !important; }
        .ecommerce-toast.swal2-icon-info { border-left: 5px solid #3498db !important; }
        .ecommerce-toast.swal2-icon-error { border-left: 5px solid var(--red-accent) !important; }

        /* Custom SweetAlert Premium Design */
        .premium-swal-popup {
            border-radius: 20px !important;
            padding: 2em !important;
            border: 1px solid var(--border-color) !important;
        }

        .premium-swal-title {
            font-family: 'Playfair Display', serif !important;
            font-size: 28px !important;
            margin-bottom: 10px !important;
        }

        .premium-swal-content {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 14px !important;
            color: var(--text-muted) !important;
        }

        .premium-swal-button-confirm {
            padding: 12px 30px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            border-radius: 10px !important;
        }

        .premium-swal-button-cancel {
            padding: 12px 30px !important;
            font-size: 12px !important;
            font-weight: 700 !important;
            text-transform: uppercase !important;
            letter-spacing: 1px !important;
            border-radius: 10px !important;
            background: #888888 !important;
        }

        @media (max-width: 800px) {
            .dashboard-layout { grid-template-columns: 1fr; }
            .profile-container { padding-top: 80px; }
            .menu-grid { grid-template-columns: 1fr 1fr; gap: 15px; }
            .action-card { flex-direction: column; text-align: center; padding: 20px 10px; gap: 10px; }
            .action-icon { margin-bottom: 5px; }
            .action-text p { font-size: 10px; }
            .ecommerce-toast {
                margin-bottom: 25px !important;
                border-radius: 50px !important;
                font-size: 12px !important;
                width: calc(100% - 40px) !important;
                border-color: var(--gold) !important;
                box-shadow: 0 8px 25px rgba(0,0,0,0.6) !important;
            }
        }
    </style>
</head>
<body>
    <x-navbar :showBack="true" />

    <div class="profile-container">
        <div class="dashboard-layout">
            
            <div class="profile-sidebar">
                <div class="profile-card">
                    <div class="profile-cover"></div>
                    <div class="profile-info">
                        <div class="profile-avatar-wrap">
                            @if(Auth::user()->foto)
                                <img src="{{ asset('uploads/profile/' . Auth::user()->foto) }}" alt="Foto Profil">
                            @else
                                <div class="default-avatar"><i class="fas fa-user-circle"></i></div>
                            @endif
                        </div>
                        
                        <h2 class="profile-name">{{ Auth::user()->name ?? 'Pengguna' }}</h2>
                        <p class="profile-email">{{ Auth::user()->email ?? 'user@domain.com' }}</p>
                        
                        @php 
                            $vip = Auth::user()->vip_paket ?? 'REGULER'; 
                            $daysRemaining = null;
                            if ($vip !== 'REGULER' && Auth::user()->member_until) {
                                $expiryDate = \Carbon\Carbon::parse(Auth::user()->member_until);
                                $daysRemaining = \Carbon\Carbon::now()->startOfDay()->diffInDays($expiryDate->startOfDay(), false);
                            }
                        @endphp
                        <div class="badge-vip {{ $vip == 'REGULER' ? 'reguler' : '' }}">
                            <i class="fas {{ $vip == 'REGULER' ? 'fa-user' : 'fa-crown' }}" style="margin-right: 5px;"></i> {{ $vip }}
                        </div>

                        @if($vip !== 'REGULER' && Auth::user()->member_until)
                            <div class="membership-expiry" style="margin-top: 15px; font-size: 11px; color: var(--text-muted); line-height: 1.6; text-align: center;">
                                <span><i class="far fa-calendar-alt" style="color: var(--gold); margin-right: 3px;"></i> Berlaku Hingga:</span>
                                <div style="font-weight: 600; color: var(--text-main); margin-top: 2px;">
                                    {{ \Carbon\Carbon::parse(Auth::user()->member_until)->locale('id')->translatedFormat('d F Y') }}
                                </div>
                                @if($daysRemaining !== null)
                                    @if($daysRemaining > 0 && $daysRemaining <= 3)
                                        <div style="color: #e67e22; font-weight: 700; margin-top: 5px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; animation: flash 1s infinite alternate;">
                                            <i class="fas fa-exclamation-triangle"></i> Sisa {{ $daysRemaining }} Hari Lagi!
                                        </div>
                                    @elseif($daysRemaining == 0)
                                        <div style="color: #e74c3c; font-weight: 700; margin-top: 5px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; animation: flash 1s infinite alternate;">
                                            <i class="fas fa-exclamation-circle"></i> Habis Hari Ini!
                                        </div>
                                    @elseif($daysRemaining > 3)
                                        <div style="color: #2ecc71; font-weight: 600; margin-top: 5px; font-size: 10px;">
                                            Sisa {{ $daysRemaining }} Hari
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="profile-balance">
                        <span class="balance-label">Saldo ERNA Pay</span>
                        <div class="balance-amount">Rp {{ number_format(Auth::user()->saldo ?? 0, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <div class="menu-grid">
                
                <a href="{{ url('/ubah-profil') }}" class="action-card">
                    <div class="action-icon"><i class="far fa-user-circle"></i></div>
                    <div class="action-text">
                        <h4>Ubah Profil</h4>
                        <p>Atur nama, email, foto, dan kontak.</p>
                    </div>
                </a>

                <a href="{{ url('/saldo-erna-pay') }}" class="action-card">
                    <div class="action-icon"><i class="fas fa-wallet"></i></div>
                    <div class="action-text">
                        <h4>Saldo ERNA Pay</h4>
                        <p>Top up saldo dan lihat riwayat Anda.</p>
                    </div>
                </a>

                <a href="{{ url('/membership-vip') }}" class="action-card">
                    <div class="action-icon"><i class="fas fa-gem"></i></div>
                    <div class="action-text">
                        <h4>Membership VIP</h4>
                        <p>Upgrade untuk fasilitas gratis ongkir.</p>
                    </div>
                </a>

                <a href="{{ url('/riwayat-pesanan') }}" class="action-card">
                    <div class="action-icon"><i class="fas fa-shopping-bag"></i></div>
                    <div class="action-text">
                        <h4>Pesanan Saya</h4>
                        <p>Lacak paket dan riwayat belanja.</p>
                    </div>
                </a>

                <!-- TOMBOL GANTI TEMA (BARU) -->
                <div class="action-card" onclick="toggleTheme()">
                    <div class="action-icon"><i class="fas fa-sun" id="theme-icon"></i></div>
                    <div class="action-text">
                        <h4 id="theme-title">Mode Terang</h4>
                        <p id="theme-desc">Ubah tampilan menjadi warna putih.</p>
                    </div>
                </div>

                <a href="{{ url('/bantuan-faq') }}" class="action-card">
                    <div class="action-icon"><i class="far fa-question-circle"></i></div>
                    <div class="action-text">
                        <h4>Bantuan & FAQ</h4>
                        <p>Pusat bantuan pelanggan ERNA.</p>
                    </div>
                </a>

                <a href="#" onclick="confirmLogout(event)" class="action-card card-logout">
                    <div class="action-icon"><i class="fas fa-sign-out-alt"></i></div>
                    <div class="action-text">
                        <h4>Keluar Akun</h4>
                        <p>Akhiri sesi belanja Anda dengan aman.</p>
                    </div>
                </a>
                
            </div>
        </div>
    </div>

    <script>
        // === LOGIKA TEMA (DARK/LIGHT MODE) ===
        document.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('erna_theme');
            
            // Periksa preferensi tersimpan
            if (savedTheme === 'light') {
                document.body.classList.add('light-mode');
                updateThemeUI('light');
            } else {
                updateThemeUI('dark');
            }
        });

        function toggleTheme() {
            const body = document.body;
            body.classList.toggle('light-mode');
            
            let currentTheme = 'dark';
            if (body.classList.contains('light-mode')) {
                currentTheme = 'light';
            }
            
            // Simpan ke local storage agar permanen
            localStorage.setItem('erna_theme', currentTheme);
            updateThemeUI(currentTheme);
        }

        function updateThemeUI(theme) {
            const iconElement = document.getElementById('theme-icon');
            const titleElement = document.getElementById('theme-title');
            const descElement = document.getElementById('theme-desc');
            
            if (theme === 'light') {
                iconElement.className = 'fas fa-moon'; 
                titleElement.innerText = 'Mode Gelap';
                descElement.innerText = 'Kembali ke nuansa hitam premium.';
            } else {
                iconElement.className = 'fas fa-sun'; 
                titleElement.innerText = 'Mode Terang';
                descElement.innerText = 'Ubah tampilan menjadi warna putih.';
            }
        }

        // === NOTIFIKASI & LOGOUT ===
        const Toast = Swal.mixin({
            toast: true,
            position: window.innerWidth <= 576 ? 'bottom' : 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: 'var(--bg-surface)',
            color: 'var(--text-main)',
            customClass: {
                popup: 'ecommerce-toast'
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: "{{ session('success') }}",
                iconColor: '#D4AF37'
            });
        @endif

        @if(session('info'))
            Toast.fire({
                icon: 'info',
                title: "{{ session('info') }}"
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: "{{ session('error') }}"
            });
        @endif

        function confirmLogout(e) {
            e.preventDefault();
            
            // Warna background dinamis untuk sweetalert menyesuaikan tema
            let swalBg = document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a';
            let swalColor = document.body.classList.contains('light-mode') ? '#111111' : '#ffffff';

            Swal.fire({
                title: 'Akhiri Sesi?',
                text: "Apakah Anda yakin ingin keluar dari akun ERNA Thrifting?",
                icon: 'warning',
                iconColor: '#e74c3c',
                showCancelButton: true,
                confirmButtonColor: '#e74c3c',
                cancelButtonColor: '#888888',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                background: swalBg,
                color: swalColor,
                customClass: {
                    popup: 'premium-swal-popup',
                    title: 'premium-swal-title',
                    htmlContainer: 'premium-swal-content',
                    confirmButton: 'premium-swal-button-confirm',
                    cancelButton: 'premium-swal-button-cancel'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInUp animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutDown animate__faster'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ url('/logout') }}";
                }
            });
        }
    </script>

    <x-footer />
</body>
</html>