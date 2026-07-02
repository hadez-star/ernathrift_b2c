@props(['showSearch' => false, 'showBack' => false])

@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
    $cartCount = Auth::check() ? \App\Models\Cart::where('user_id', Auth::id())->sum('jumlah') : 0;
@endphp

<style>
    /* --- NAVBAR COMPONENT STYLES --- */
    .navbar { 
        display: flex; justify-content: space-between; align-items: center; 
        padding: 25px 60px; width: 100%; position: fixed; top: 0; z-index: 1000; 
        transition: 0.4s ease; background: transparent; border-bottom: 1px solid transparent; 
    }
    :root {
        --bg-surface-translucent: rgba(26, 26, 26, 0.85);
    }
    body.light-mode {
        --bg-surface-translucent: rgba(255, 255, 255, 0.85);
    }

    .navbar.scrolled, .navbar.solid { 
        background: var(--bg-surface-translucent, var(--bg-surface)); 
        backdrop-filter: blur(15px); 
        -webkit-backdrop-filter: blur(15px);
        padding: 15px 60px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); 
        border-bottom: 1px solid var(--border-color); 
    }
    
    .navbar-brand { 
        font-family: 'Playfair Display', serif; font-size: 22px; font-weight: 700; 
        color: var(--gold); letter-spacing: 2px; text-decoration: none; 
        text-transform: uppercase; transition: 0.3s;
        z-index: 10;
        position: absolute; left: 50%; transform: translateX(-50%); text-align: center;
    }

    .search-navbar-wrapper { flex: 2; display: flex; justify-content: center; }
    
    .search-navbar { 
        display: flex; align-items: center; border-radius: 30px; 
        padding: 5px 20px; width: 100%; max-width: 400px; 
        transition: all 0.3s ease; position: relative;
    }
    body:not(.light-mode) .search-navbar { background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.1); }
    body.light-mode .search-navbar { background: rgba(0, 0, 0, 0.05); border: 1px solid rgba(0, 0, 0, 0.1); }
    
    .search-navbar:hover { background: rgba(212, 175, 55, 0.1) !important; border-color: var(--gold) !important; }
    .search-navbar input { background: transparent !important; border: none !important; outline: none !important; padding: 10px 0; color: var(--text-main) !important; font-size: 13px; width: 100%; font-family: 'Montserrat', sans-serif; }
    .search-navbar button { background: none; border: none; color: var(--text-muted); cursor: pointer; transition: 0.3s; padding-left: 10px; }

    .nav-links { display: flex; align-items: center; gap: 30px; flex: 1; justify-content: flex-end; }
    .nav-item { color: var(--text-main); text-decoration: none; font-size: 11px; font-weight: 600; letter-spacing: 2px; transition: 0.3s; cursor: pointer; text-transform: uppercase; }
    .nav-item:hover { color: var(--gold) !important; }

    .btn-back-nav { display: flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-size: 12px; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px; flex: 1; }
    .btn-back-nav:hover { color: var(--gold); transform: translateX(-3px); }

    /* DROPDOWN */
    .nav-dropdown { position: relative; padding: 10px 0; }
    .dropdown-menu { visibility: hidden; opacity: 0; position: absolute; top: 100%; right: 0; background: var(--bg-surface); min-width: 200px; padding: 15px 0; box-shadow: 0 15px 40px rgba(0,0,0,0.1); border-radius: 8px; border: 1px solid var(--border-color); transition: 0.3s; z-index: 100; transform: translateY(10px); }
    .nav-dropdown:hover .dropdown-menu { visibility: visible; opacity: 1; transform: translateY(0); }
    .dropdown-item { display: block; padding: 10px 20px; color: var(--text-main); text-decoration: none; font-size: 11px; font-weight: 500; transition: 0.3s; letter-spacing: 1px; }
    .dropdown-item:hover { background: rgba(212, 175, 55, 0.05); color: var(--gold); padding-left: 25px; }

    .cart-wrapper { position: relative; cursor: pointer; text-decoration: none; font-size: 16px;}
    .cart-badge { position: absolute; top: -6px; right: -12px; background: var(--gold); color: #fff; font-size: 9px; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: 700; }

    /* MOBILE DRAWER STYLES */
    .mobile-overlay { 
        position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
        background: rgba(0,0,0,0.4); backdrop-filter: blur(4px); 
        z-index: 2000; opacity: 0; visibility: hidden; transition: 0.4s ease; 
    }
    .mobile-overlay.active { opacity: 1; visibility: visible; }

    .mobile-drawer { 
        position: fixed; top: 0; right: -320px; width: 320px; height: 100%; 
        background: var(--bg-surface); z-index: 2100; 
        box-shadow: -10px 0 30px rgba(0,0,0,0.3); transition: 0.5s cubic-bezier(0.85, 0, 0.15, 1); 
        display: flex; flex-direction: column; padding: 30px;
        border-left: 1px solid var(--border-color);
    }
    body:not(.light-mode) .mobile-drawer { background: linear-gradient(135deg, #1a1a1a 0%, #0f0f0f 100%); }
    .mobile-overlay.active .mobile-drawer { right: 0; }

    .drawer-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
    .drawer-brand { font-family: 'Playfair Display', serif; font-size: 18px; font-weight: 700; color: var(--gold); letter-spacing: 1px; text-transform: uppercase; }
    .close-drawer { font-size: 24px; color: var(--text-muted); cursor: pointer; transition: 0.3s; }
    .close-drawer:hover { color: var(--gold); transform: rotate(90deg); }

    .mobile-nav-list { list-style: none; padding: 0; flex-grow: 1; }
    .mobile-nav-list li { margin-bottom: 20px; opacity: 0; transform: translateX(20px); transition: 0.4s ease; }
    .mobile-overlay.active .mobile-nav-list li { opacity: 1; transform: translateX(0); }
    
    /* Staggered delay for items */
    .mobile-nav-list li:nth-child(1) { transition-delay: 0.1s; }
    .mobile-nav-list li:nth-child(2) { transition-delay: 0.15s; }
    .mobile-nav-list li:nth-child(3) { transition-delay: 0.2s; }
    .mobile-nav-list li:nth-child(4) { transition-delay: 0.25s; }
    .mobile-nav-list li:nth-child(5) { transition-delay: 0.3s; }
    .mobile-nav-list li:nth-child(6) { transition-delay: 0.35s; }

    .mobile-nav-link { 
        display: flex; align-items: center; gap: 15px; color: var(--text-main); 
        text-decoration: none; font-size: 14px; font-weight: 600; letter-spacing: 1px; 
        text-transform: uppercase; transition: 0.3s; padding: 12px 15px; 
        border-radius: 12px; border: 1px solid transparent;
    }
    .mobile-nav-link i { font-size: 18px; color: var(--gold); width: 24px; text-align: center; }
    .mobile-nav-link:hover { background: rgba(212, 175, 55, 0.05); border-color: rgba(212, 175, 55, 0.2); color: var(--gold); }
    .mobile-nav-link.active { background: var(--gold); color: #fff; }
    .mobile-nav-link.active i { color: #fff; }

    .drawer-footer { border-top: 1px solid var(--border-color); padding-top: 25px; margin-top: auto; }
    .profile-mini { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
    .profile-mini-img { width: 45px; height: 45px; border-radius: 50%; border: 2px solid var(--gold); padding: 2px; }
    .profile-mini-info h4 { font-size: 14px; color: var(--text-main); font-weight: 700; margin: 0; }
    .profile-mini-info p { font-size: 11px; color: var(--text-muted); margin: 0; }

    .mobile-controls { display: none; }

    @media (max-width: 992px) {
        .navbar { padding: 15px 20px; display: flex; justify-content: flex-start; align-items: center; }
        .navbar.scrolled, .navbar.solid { padding: 12px 20px; }
        .nav-links, .search-navbar-wrapper { display: none; }
        .mobile-controls { display: flex; align-items: center; gap: 10px; z-index: 10; margin-left: auto; }
        .mobile-btn { 
            display: flex; align-items: center; justify-content: center; 
            width: 38px; height: 38px; border-radius: 10px; 
            background: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.2); 
            color: var(--text-main); cursor: pointer; transition: 0.3s;
        }
        .mobile-btn:hover { background: var(--gold); color: #fff; }
        .mobile-btn i { font-size: 16px; }
        .navbar-brand { 
            position: absolute; left: 50%; transform: translateX(-50%);
            font-size: 16px; white-space: nowrap; flex: none !important;
            text-align: center; width: auto; z-index: 1;
        }
        .btn-back-nav { flex: none; width: auto; font-size: 10px; z-index: 10; margin-right: auto; }
    }

    @media (max-width: 480px) {
        .navbar-brand { font-size: 14px; letter-spacing: 1px; }
        .btn-back-nav span { display: none; }
    }

    /* MOBILE SEARCH BAR */
    .mobile-search-overlay {
        position: fixed; top: 70px; left: 0; width: 100%; 
        background: var(--bg-surface); padding: 15px 25px;
        border-bottom: 1px solid var(--border-color);
        z-index: 999; transform: translateY(-100%);
        visibility: hidden; transition: 0.4s cubic-bezier(0.85, 0, 0.15, 1);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .mobile-search-overlay.active { transform: translateY(0); visibility: visible; }
    .mobile-search-form { display: flex; align-items: center; background: var(--input-bg); border-radius: 12px; padding: 5px 15px; border: 1px solid var(--border-color); }
    .mobile-search-form input { flex: 1; background: transparent; border: none; outline: none; padding: 10px 0; color: var(--text-main); font-size: 14px; font-family: 'Montserrat', sans-serif; }
    .mobile-search-form button { background: none; border: none; color: var(--gold); font-size: 16px; margin-left: 10px; }
</style>

<nav class="navbar {{ Request::is('/') ? '' : 'solid' }}" id="navbar">
    @if($showBack)
        <a href="{{ url('/') }}" class="btn-back-nav"><i class="fas fa-arrow-left"></i> <span>Beranda</span></a>
    @endif

    <a href="/" class="navbar-brand">{{ $nama_toko }}</a>

    @if($showSearch)
        <div class="search-navbar-wrapper">
            <form action="/" method="GET" class="search-navbar" style="position: relative;">
                <input type="text" name="search" id="live-search-input" value="{{ request('search') }}" placeholder="Cari koleksi eksklusif..." autocomplete="off">
                <button type="submit"><i class="fas fa-search"></i></button>
                <div id="live-search-results" class="search-results-dropdown"></div>
            </form>
        </div>
    @endif

    <div class="nav-links">
        <div class="nav-dropdown">
            <a class="nav-item">KATALOG <i class="fas fa-chevron-down" style="font-size: 9px; margin-left: 4px;"></i></a>
            <div class="dropdown-menu">
                <a href="{{ url('/katalog/baju-pria') }}" class="dropdown-item">Baju Pria</a>
                <a href="{{ url('/katalog/celana-pria') }}" class="dropdown-item">Celana Pria</a>
                <a href="{{ url('/katalog/baju-wanita') }}" class="dropdown-item">Baju Wanita</a>
                <a href="{{ url('/katalog/celana-wanita') }}" class="dropdown-item">Celana Wanita</a>
                <a href="{{ url('/katalog/hoodie') }}" class="dropdown-item">Hoodie</a>
                <div style="border-top: 1px solid var(--border-color); margin: 5px 0;"></div>
                <a href="{{ url('/flash-sale') }}" class="dropdown-item" style="color: var(--red-accent);"><i class="fas fa-bolt" style="margin-right: 5px;"></i> Flash Sale</a>
            </div>
        </div>
        
        @auth
            <div class="nav-dropdown">
                <a class="nav-item">PROFIL <i class="fas fa-chevron-down" style="font-size: 9px; margin-left: 4px;"></i></a>
                <div class="dropdown-menu">
                    <a href="{{ url('/profile') }}" class="dropdown-item">Profil Saya</a>
                    <a href="{{ url('/logout') }}" class="dropdown-item" style="color: var(--red-accent);">Keluar</a>
                </div>
            </div>
            <div class="nav-dropdown">
                <a href="{{ url('/keranjang') }}" class="nav-item cart-wrapper">
                    <i class="fas fa-shopping-bag"></i>
                    @if($cartCount > 0)
                        <span class="cart-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <div class="dropdown-menu">
                    <a href="{{ url('/keranjang') }}" class="dropdown-item"><i class="fas fa-shopping-cart" style="margin-right:8px; color:var(--gold);"></i> Keranjang</a>
                    <a href="{{ url('/wishlist') }}" class="dropdown-item"><i class="fas fa-heart" style="margin-right:8px; color:var(--red-accent);"></i> Wishlist</a>
                    <a href="{{ url('/riwayat-pesanan') }}" class="dropdown-item"><i class="fas fa-receipt" style="margin-right:8px; color:var(--text-muted);"></i> Riwayat Pesanan</a>
                </div>
            </div>

            @include('components.notif-bell')
        @else
            <a href="{{ url('/login') }}" class="nav-item">MASUK / DAFTAR</a>
        @endauth
    </div>

    <!-- MOBILE ONLY CONTROLS -->
    <div class="mobile-controls">
        <div class="mobile-btn" id="mobileSearchToggle">
            <i class="fas fa-search"></i>
        </div>
        <div class="mobile-btn mobile-menu-toggle">
            <i class="fas fa-bars-staggered"></i>
        </div>
    </div>
</nav>

<!-- MOBILE SEARCH OVERLAY -->
<div class="mobile-search-overlay" id="mobileSearchOverlay">
    <form action="/" method="GET" class="mobile-search-form" style="position: relative;">
        <input type="text" name="search" placeholder="Cari produk..." value="{{ request('search') }}" id="mobileSearchInput" autocomplete="off">
        <button type="submit"><i class="fas fa-search"></i></button>
        <div id="mobile-live-search-results" class="search-results-dropdown"></div>
    </form>
</div>

<!-- MOBILE DRAWER MENU -->
<div class="mobile-overlay" id="mobileMenu">
    <div class="mobile-drawer">
        <div class="drawer-header">
            <span class="drawer-brand">{{ $nama_toko }}</span>
            <i class="fas fa-times close-drawer"></i>
        </div>
        
        <ul class="mobile-nav-list">
            <small style="color: var(--text-muted); font-size: 10px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; display: block;">Navigasi Utama</small>
            <li><a href="{{ url('/') }}" class="mobile-nav-link {{ Request::is('/') ? 'active' : '' }}"><i class="fas fa-home"></i> Beranda</a></li>
            <li><a href="{{ url('/katalog/semua') }}" class="mobile-nav-link {{ Request::is('katalog*') ? 'active' : '' }}"><i class="fas fa-th-large"></i> Katalog</a></li>
            <li><a href="{{ url('/flash-sale') }}" class="mobile-nav-link {{ Request::is('flash-sale*') ? 'active' : '' }}" style="color: var(--red-accent);"><i class="fas fa-bolt"></i> Flash Sale</a></li>
            
            <div style="border-top: 1px solid var(--border-color); margin: 15px 0; opacity: 0.5;"></div>
            <small style="color: var(--text-muted); font-size: 10px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 10px; display: block;">Belanja & Transaksi</small>
            <li><a href="{{ url('/keranjang') }}" class="mobile-nav-link {{ Request::is('keranjang*') ? 'active' : '' }}"><i class="fas fa-shopping-bag"></i> Keranjang @if($cartCount > 0) ({{ $cartCount }}) @endif</a></li>
            @auth
                <li><a href="{{ url('/wishlist') }}" class="mobile-nav-link {{ Request::is('wishlist*') ? 'active' : '' }}"><i class="fas fa-heart" style="color: var(--red-accent);"></i> Wishlist</a></li>
                <li><a href="{{ url('/riwayat-pesanan') }}" class="mobile-nav-link {{ Request::is('riwayat-pesanan*') ? 'active' : '' }}"><i class="fas fa-receipt"></i> Riwayat Pesanan</a></li>
            @endauth
            
            @guest
                <li><a href="{{ url('/login') }}" class="mobile-nav-link"><i class="fas fa-sign-in-alt"></i> Masuk / Daftar</a></li>
            @endguest
        </ul>

        <div class="drawer-footer">
            @auth
                <div class="profile-mini">
                    <div class="profile-mini-img">
                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=D4AF37&color=fff' }}" alt="Avatar" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                    </div>
                    <div class="profile-mini-info">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p>{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <a href="{{ url('/profile') }}" class="mobile-nav-link" style="margin-bottom: 10px;"><i class="fas fa-user-circle"></i> Profil Saya</a>
                <a href="{{ url('/logout') }}" class="mobile-nav-link" style="color: var(--red-accent);"><i class="fas fa-power-off"></i> Keluar</a>
            @else
            @endauth
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.querySelector('.mobile-menu-toggle');
        const closeBtn = document.querySelector('.close-drawer');
        const overlay = document.getElementById('mobileMenu');
        const drawer = document.querySelector('.mobile-drawer');
        
        const searchToggle = document.getElementById('mobileSearchToggle');
        const searchOverlay = document.getElementById('mobileSearchOverlay');
        const searchInput = document.getElementById('mobileSearchInput');

        function openMenu() {
            if (searchOverlay.classList.contains('active')) {
                searchOverlay.classList.remove('active');
            }
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMenu() {
            overlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        if (toggleBtn) toggleBtn.addEventListener('click', openMenu);
        if (closeBtn) closeBtn.addEventListener('click', closeMenu);

        if (searchToggle) {
            searchToggle.addEventListener('click', function() {
                searchOverlay.classList.toggle('active');
                if (searchOverlay.classList.contains('active')) {
                    setTimeout(() => searchInput.focus(), 300);
                }
            });
        }
        
        // Close when clicking overlay (outside drawer)
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) closeMenu();
        });

        // Close menu when clicking on a link
        const mobileLinks = document.querySelectorAll('.mobile-nav-link');
        mobileLinks.forEach(link => {
            link.addEventListener('click', closeMenu);
        });

        // LIVE SEARCH LOGIC (Unified for Desktop & Mobile)
        function initLiveSearch(inputId, resultsId) {
            const input = document.getElementById(inputId);
            const results = document.getElementById(resultsId);
            let timeout;

            if (!input || !results) return;

            input.addEventListener('input', function() {
                clearTimeout(timeout);
                const query = this.value;

                if (query.length < 2) {
                    results.style.display = 'none';
                    return;
                }

                timeout = setTimeout(() => {
                    fetch(`/api/produk/search?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.length > 0) {
                                let html = '';
                                data.forEach(item => {
                                    html += `
                                        <a href="${item.url}" class="search-result-item">
                                            <img src="${item.gambar}" class="search-result-img" onerror="this.src='https://via.placeholder.com/40?text=?'">
                                            <div class="search-result-info">
                                                <span class="search-result-name">${item.nama}</span>
                                                <span class="search-result-price">${item.harga}</span>
                                            </div>
                                        </a>
                                    `;
                                });
                                results.innerHTML = html;
                                results.style.display = 'block';
                            } else {
                                results.style.display = 'none';
                            }
                        });
                }, 300);
            });

            // Close results when clicking outside
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !results.contains(e.target)) {
                    results.style.display = 'none';
                }
            });
        }

        // Initialize for both desktop and mobile
        initLiveSearch('live-search-input', 'live-search-results');
        initLiveSearch('mobileSearchInput', 'mobile-live-search-results');
    });

    // Scroll effect for navbar
    window.addEventListener('scroll', function() {
        var nav = document.getElementById('navbar');
        if (nav) {
            if (window.pageYOffset > 50) nav.classList.add("scrolled"); 
            else nav.classList.remove("scrolled");
        }
    });
</script>
