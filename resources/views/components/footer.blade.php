@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
    $email_toko = $setting->email ?? 'hello@ernathrifting.com';
    $wa_toko = $setting->whatsapp ?? '6281234567890';
    $alamat_toko = $setting->alamat ?? 'Pontianak, Indonesia';
    $deskripsi_toko = $setting->deskripsi ?? 'Menghadirkan koleksi pakaian bekas berkualitas premium yang dikurasi dengan ketat. Kami percaya bahwa gaya yang memukau tidak harus merusak bumi.';
@endphp

<footer class="footer">
    <div class="footer-grid">
        <div class="footer-section">
            <h2 class="footer-brand">{{ $nama_toko }}</h2>
            <p class="footer-tagline">{{ $deskripsi_toko }}</p>
            <div style="margin-top: 25px; display: flex; gap: 15px;">
                <a href="https://wa.me/{{ $wa_toko }}" target="_blank" class="btn-social"><i class="fab fa-whatsapp"></i></a>
                <a href="#" class="btn-social"><i class="fab fa-instagram"></i></a>
                <a href="#" class="btn-social"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>
        <div class="footer-section">
            <h3 class="footer-heading">LAYANAN</h3>
            <ul class="footer-links">
                <li><a href="{{ url('/cara-pemesanan') }}">Cara Pemesanan</a></li>
                <li><a href="{{ url('/panduan-ukuran') }}">Panduan Ukuran</a></li>
                <li><a href="{{ url('/kebijakan-pengembalian') }}">Kebijakan Pengembalian</a></li>
                <li><a href="{{ url('/syarat-ketentuan') }}">Syarat & Ketentuan</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3 class="footer-heading">AKUN SAYA</h3>
            <ul class="footer-links">
                <li><a href="{{ url('/profile') }}">Profil Saya</a></li>
                <li><a href="{{ url('/riwayat-pesanan') }}">Pesanan Saya</a></li>
                <li><a href="{{ url('/keranjang') }}">Keranjang Belanja</a></li>
                @auth
                <li><a href="{{ url('/saldo-erna-pay') }}">Saldo ERNA Pay</a></li>
                @endauth
            </ul>
        </div>
        <div class="footer-section">
            <h3 class="footer-heading">KONTAK</h3>
            <ul class="footer-contact">
                <li><i class="fas fa-map-marker-alt"></i> {{ $alamat_toko }}</li>
                <li><i class="far fa-envelope"></i> {{ $email_toko }}</li>
                <li><i class="fas fa-phone-alt"></i> +{{ $wa_toko }}</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="footer-copyright">&copy; 2026 {{ $nama_toko }} BESPOKE & THRIFT. ALL RIGHTS RESERVED.</div>
    </div>
</footer>

<div class="back-to-top" id="backToTop" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
    <i class="fas fa-arrow-up"></i>
</div>

<script>
    window.onscroll = function() {
        var backToTop = document.getElementById('backToTop');
        if (backToTop) {
            if (window.pageYOffset > 500) backToTop.classList.add("show");
            else backToTop.classList.remove("show");
        }
    };
</script>

<style>
    /* FOOTER STYLES */
    .footer { 
        background-color: var(--bg-dark); 
        border-top: 1px solid var(--border-color); 
        padding: 80px 5% 40px; 
        transition: 0.4s ease;
        margin-top: auto;
    }
    .footer-grid { 
        display: grid; 
        grid-template-columns: 1.5fr 1fr 1fr 1fr; 
        gap: 60px; 
        max-width: 1200px; 
        margin: 0 auto 60px; 
    }
    .footer-brand { 
        font-family: 'Playfair Display', serif; 
        font-size: 26px; 
        font-weight: 700; 
        color: var(--gold); 
        margin-bottom: 25px; 
        letter-spacing: 1px; 
    }
    .footer-tagline { 
        font-size: 13px; 
        color: var(--text-muted); 
        line-height: 1.8; 
        max-width: 320px;
    }
    .footer-heading {
        font-size: 11px; 
        color: var(--text-main); 
        margin-bottom: 30px; 
        letter-spacing: 2px;
        text-transform: uppercase;
        font-weight: 700;
    }
    .btn-social { 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        width: 38px; 
        height: 38px; 
        background-color: rgba(136,136,136,0.05); 
        color: var(--text-main); 
        border: 1px solid var(--border-color); 
        border-radius: 50%; 
        font-size: 16px; 
        text-decoration: none; 
        transition: 0.3s; 
    }
    .btn-social:hover { 
        background: var(--gold); 
        color: #111; 
        border-color: var(--gold); 
        transform: translateY(-3px);
    }
    .footer-links { list-style: none; padding: 0; }
    .footer-links li { margin-bottom: 15px; }
    .footer-links a { 
        color: var(--text-muted); 
        text-decoration: none; 
        font-size: 12px; 
        transition: 0.3s; 
        cursor: pointer; 
        letter-spacing: 0.5px; 
    }
    .footer-links a:hover { 
        color: var(--gold); 
        padding-left: 5px; 
    }
    .footer-contact { list-style: none; font-size: 12px; color: var(--text-muted); line-height: 2.2; padding: 0; }
    .footer-contact i { margin-right: 12px; color: var(--gold); width: 15px; text-align: center; }

    .footer-bottom {
        border-top: 1px solid var(--border-color);
        padding-top: 30px;
        text-align: center;
    }
    .footer-copyright {
        font-size: 10px; 
        color: var(--text-muted); 
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    /* BACK TO TOP */
    .back-to-top {
        position: fixed; bottom: 30px; right: 30px; width: 45px; height: 45px;
        background: var(--bg-surface); border: 1px solid var(--border-color);
        color: var(--gold); border-radius: 50%; display: flex; align-items: center; justify-content: center;
        cursor: pointer; opacity: 0; visibility: hidden; transition: 0.4s; z-index: 999;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    .back-to-top.show { opacity: 1; visibility: visible; }
    .back-to-top:hover { background: var(--gold); color: #111; border-color: var(--gold); transform: translateY(-5px); }

    @media (max-width: 1024px) {
        .footer-grid { grid-template-columns: 1fr 1fr; gap: 40px; }
    }
    @media (max-width: 600px) {
        .footer { padding: 60px 20px 30px; }
        .footer-grid { grid-template-columns: 1fr; gap: 40px; text-align: center; }
        .footer-tagline { margin: 0 auto; }
        .btn-social { margin: 0 auto; }
        .footer-section { display: flex; flex-direction: column; align-items: center; }
        .footer-contact li { display: flex; align-items: center; justify-content: center; }
    }
</style>
