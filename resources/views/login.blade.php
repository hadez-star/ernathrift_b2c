@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | ERNA Thrifting Premium</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --gold: #D4AF37;
            --gold-hover: #bda038;
            --bg-dark: #0f0f0f;
            --bg-surface: #1a1a1a;
            --text-main: #f5f5f5;
            --text-muted: #888888;
            --border-color: #333333;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* --- SPLIT SCREEN LAYOUT --- */
        .split-layout { display: flex; width: 100%; min-height: 100vh; }

        .brand-side {
            flex: 1;
            background: linear-gradient(rgba(15, 15, 15, 0.7), rgba(15, 15, 15, 0.9)), 
                        url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop') center/cover no-repeat;
            display: flex; flex-direction: column; justify-content: center; padding: 80px; position: relative;
        }
        .brand-side::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, transparent 0%, var(--bg-dark) 100%);
        }
        .brand-content { position: relative; z-index: 1; max-width: 500px; }
        .brand-title { font-family: 'Playfair Display', serif; font-size: 56px; font-weight: 700; color: var(--gold); line-height: 1.1; margin-bottom: 20px; letter-spacing: -1px; }
        .brand-tagline { font-size: 14px; color: var(--text-main); line-height: 1.8; font-weight: 300; letter-spacing: 1px; }

        .form-side { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px; background-color: var(--bg-dark); position: relative; }
        .form-container { width: 100%; max-width: 400px; animation: fadeInRight 0.8s ease-out forwards; }

        @keyframes fadeInRight { from { opacity: 0; transform: translateX(30px); } to { opacity: 1; transform: translateX(0); } }

        /* --- TABS --- */
        .tabs { display: flex; margin-bottom: 40px; border-bottom: 1px solid var(--border-color); position: relative; }
        .tab { flex: 1; padding: 15px 0; text-align: center; font-size: 13px; font-weight: 600; color: var(--text-muted); cursor: pointer; text-transform: uppercase; letter-spacing: 2px; transition: color 0.3s; }
        .tab.active { color: var(--gold); }
        .tab-indicator { position: absolute; bottom: -1px; left: 0; width: 50%; height: 2px; background-color: var(--gold); transition: transform 0.4s cubic-bezier(0.25, 1, 0.5, 1); }

        /* --- FLOATING LABEL INPUTS --- */
        .floating-group { position: relative; margin-bottom: 30px; }
        .floating-input {
            width: 100%; padding: 20px 40px 10px 0; /* Padding kanan diubah agar teks tidak nabrak ikon mata */
            font-size: 15px; color: var(--text-main); background: transparent;
            border: none; border-bottom: 1px solid var(--border-color); outline: none; transition: border-color 0.3s; font-family: 'Montserrat', sans-serif;
        }
        .floating-label { position: absolute; top: 15px; left: 0; font-size: 14px; color: var(--text-muted); transition: all 0.3s ease; pointer-events: none; }
        .floating-input:focus, .floating-input:not(:placeholder-shown) { border-bottom-color: var(--gold); }
        .floating-input:focus ~ .floating-label, .floating-input:not(:placeholder-shown) ~ .floating-label { top: -5px; font-size: 11px; color: var(--gold); letter-spacing: 1px; text-transform: uppercase; }
        
        /* PERBAIKAN IKON MATA (Z-INDEX & HITBOX) */
        .password-toggle { 
            position: absolute; right: -10px; top: 10px; cursor: pointer; color: var(--text-muted); 
            transition: 0.3s; z-index: 10; padding: 10px; /* Area klik lebih besar */
        }
        .password-toggle:hover { color: var(--gold); }
        
        .char-counter {
            position: absolute;
            right: 0;
            bottom: -18px;
            font-size: 10px;
            color: #555;
        }

        /* Error state input */
        .floating-input.is-error { border-bottom-color: #ef4444 !important; }
        .field-error {
            position: absolute;
            bottom: -20px;
            left: 0;
            font-size: 10px;
            color: #ef4444;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* --- OPSI & TOMBOL --- */
        .options { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; margin-top: 10px; }
        .checkbox-container { display: flex; align-items: center; cursor: pointer; font-size: 12px; color: var(--text-muted); user-select: none; }
        .checkbox-container input { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
        .checkmark { height: 16px; width: 16px; background-color: transparent; border: 1px solid var(--border-color); border-radius: 3px; margin-right: 10px; display: flex; align-items: center; justify-content: center; transition: 0.2s; }
        .checkbox-container input:checked ~ .checkmark { background-color: var(--gold); border-color: var(--gold); }
        .checkmark:after { content: "\f00c"; font-family: "Font Awesome 5 Free"; font-weight: 900; font-size: 10px; color: var(--bg-dark); display: none; }
        .checkbox-container input:checked ~ .checkmark:after { display: block; }

        .forgot-link { font-size: 12px; color: var(--text-muted); text-decoration: none; transition: 0.3s; cursor: pointer; }
        .forgot-link:hover { color: var(--gold); text-shadow: 0 0 10px rgba(212, 175, 55, 0.3); }

        .btn-submit {
            width: 100%; padding: 16px; background-color: var(--gold); color: var(--bg-dark); border: none; font-family: 'Montserrat', sans-serif;
            font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: background 0.3s, transform 0.3s;
            display: flex; justify-content: center; align-items: center; gap: 10px; margin-top: 15px;
        }
        .btn-submit:hover { background-color: var(--gold-hover); }

        /* --- CUSTOM TOAST NOTIFICATION --- */
        .custom-toast {
            position: fixed; top: 30px; right: -400px; background: var(--bg-surface);
            border-left: 4px solid var(--gold); color: var(--text-main); padding: 16px 24px;
            border-radius: 8px; box-shadow: 0 15px 30px rgba(0,0,0,0.6); display: flex;
            align-items: center; gap: 15px; z-index: 9999; font-size: 13px; font-weight: 500;
            max-width: 350px; line-height: 1.5; transition: right 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .custom-toast.show { right: 30px; }
        .custom-toast.error { border-left-color: #ef4444; }
        .custom-toast .toast-icon { font-size: 20px; color: var(--gold); }
        .custom-toast.error .toast-icon { color: #ef4444; }

        /* --- MODAL LUPA SANDI --- */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
            display: flex; justify-content: center; align-items: center;
            z-index: 1000; opacity: 0; visibility: hidden; transition: all 0.4s ease;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        
        .modal-content {
            background: var(--bg-surface); border: 1px solid var(--border-color);
            border-radius: 12px; padding: 40px; width: 90%; max-width: 400px;
            position: relative; transform: translateY(30px) scale(0.95); transition: all 0.4s ease;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        }
        .modal-overlay.active .modal-content { transform: translateY(0) scale(1); }

        .close-modal { position: absolute; top: 15px; right: 20px; font-size: 24px; color: var(--text-muted); cursor: pointer; transition: 0.3s; }
        .close-modal:hover { color: var(--gold); transform: rotate(90deg); }
        
        .modal-content h2 { font-family: 'Playfair Display', serif; color: var(--gold); margin-bottom: 15px; font-size: 24px; }
        .modal-content p { font-size: 13px; color: var(--text-muted); margin-bottom: 30px; line-height: 1.6; }

        @media (max-width: 768px) {
            .brand-side { display: none; }
            .form-side { padding: 30px; }
            .form-container { max-width: 100%; }
            .mobile-brand { display: block; text-align: center; margin-bottom: 40px; }
            .mobile-brand h1 { font-family: 'Playfair Display', serif; color: var(--gold); font-size: 32px; letter-spacing: 1px; }
            .mobile-brand p { font-size: 10px; color: var(--text-muted); letter-spacing: 3px; text-transform: uppercase; margin-top: 5px; }
            .custom-toast { top: 20px; right: auto; left: 50%; transform: translateX(-50%); width: 90%; bottom: auto; transition: top 0.5s ease; top: -100px; }
            .custom-toast.show { top: 20px; right: auto; }
        }
        @media (min-width: 769px) { .mobile-brand { display: none; } }
        #register-form { display: none; }
    </style>
</head>
<body>

    <div id="custom-toast" class="custom-toast">
        <i id="toast-icon" class="fas fa-check-circle toast-icon"></i>
        <span id="toast-message">Pesan notifikasi di sini.</span>
    </div>

    <div class="split-layout">
        <div class="brand-side">
            <div class="brand-content">
                <h1 class="brand-title">ERNA.<br>Bespoke & Thrift.</h1>
                <p class="brand-tagline">Temukan mahakarya fashion berkelanjutan. Kurasi eksklusif untuk gaya hidup yang lebih bermakna.</p>
            </div>
        </div>

        <div class="form-side">
            <div class="form-container">
                
                <div class="mobile-brand">
                    <h1>ERNA.</h1>
                    <p>Bespoke & Thrift</p>
                </div>

                <div class="tabs">
                    <div class="tab active" onclick="switchTab('login', 0)">Masuk</div>
                    <div class="tab" onclick="switchTab('register', 1)">Daftar</div>
                    <div class="tab-indicator" id="tab-indicator"></div>
                </div>

                <form action="{{ route('login.process') }}" method="POST" id="login-form">
                    @csrf
                    <div class="floating-group">
                        <input type="email" name="email" id="log-email" class="floating-input" placeholder=" " required maxlength="25">
                        <label for="log-email" class="floating-label">Alamat Email</label>
                    </div>

                    <div class="floating-group">
                        <input type="password" name="password" id="log-password" class="floating-input" placeholder=" " required>
                        <label for="log-password" class="floating-label">Kata Sandi</label>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('log-password', this)"></i>
                    </div>
                    
                    <div class="options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Ingat Saya
                        </label>
                        <a onclick="openForgotModal()" class="forgot-link">Lupa Sandi?</a>
                    </div>

                    <button type="submit" class="btn-submit">
                        Masuk <i class="fas fa-arrow-right"></i>
                    </button>
                </form>

                <form action="{{ route('register.process') }}" method="POST" id="register-form">
                    @csrf
                    <div class="floating-group">
                        <input type="text" name="name" id="reg-name" class="floating-input {{ $errors->has('name') ? 'is-error' : '' }}" placeholder=" " required maxlength="100" oninput="updateCounter('reg-name', 'counter-name', 100)" value="{{ old('name') }}">
                        <label for="reg-name" class="floating-label">Nama Lengkap</label>
                        <span class="char-counter" id="counter-name">0/100</span>
                        @error('name')<span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>@enderror
                    </div>

                    <div class="floating-group">
                        <input type="email" name="email" id="reg-email" class="floating-input {{ $errors->has('email') ? 'is-error' : '' }}" placeholder=" " required maxlength="100" value="{{ old('email') }}">
                        <label for="reg-email" class="floating-label">Alamat Email</label>
                        @error('email')<span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>@enderror
                    </div>

                    <div class="floating-group">
                        <input type="password" name="password" id="reg-password" class="floating-input {{ $errors->has('password') ? 'is-error' : '' }}" placeholder=" " required minlength="8" maxlength="32">
                        <label for="reg-password" class="floating-label">Kata Sandi</label>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('reg-password', this)"></i>
                        @error('password')<span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>@else<span class="char-counter" style="left:0; right:auto;">Min. 8 karakter</span>@enderror
                    </div>

                    <div class="floating-group">
                        <input type="password" name="password_confirmation" id="reg-password-confirm" class="floating-input {{ $errors->has('password_confirmation') ? 'is-error' : '' }}" placeholder=" " required minlength="8" maxlength="32">
                        <label for="reg-password-confirm" class="floating-label">Konfirmasi Kata Sandi</label>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('reg-password-confirm', this)"></i>
                        @error('password_confirmation')<span class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</span>@enderror
                    </div>

                    <button type="submit" class="btn-submit" style="margin-top: 30px;">
                        Buat Akun <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="forgotModal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeForgotModal()">&times;</span>
            <h2>Atur Ulang Sandi</h2>
            <p>Masukkan alamat email Anda yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda dengan aman.</p>
            
            <form id="reset-form">
                <div class="floating-group">
                    <input type="email" name="reset_email" id="reset-email" class="floating-input" placeholder=" " required maxlength="25">
                    <label for="reset-email" class="floating-label">Alamat Email Terdaftar</label>
                </div>
                <button type="button" class="btn-submit" onclick="simulasiKirimReset()" style="margin-top: 25px;">
                    Kirim Tautan <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        // --- SISTEM NOTIFIKASI CUSTOM (TOAST) ---
        function showCustomToast(message, isError = false) {
            const toast = document.getElementById('custom-toast');
            const toastMessage = document.getElementById('toast-message');
            const icon = document.getElementById('toast-icon');

            toastMessage.innerText = message;

            if(isError) {
                toast.classList.add('error');
                icon.className = 'fas fa-exclamation-circle toast-icon';
            } else {
                toast.classList.remove('error');
                icon.className = 'fas fa-check-circle toast-icon';
            }

            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }

        function simulasiKirimReset() {
            const emailInput = document.getElementById('reset-email').value;
            if(emailInput) {
                closeForgotModal();
                showCustomToast("Tautan pengaturan ulang sandi telah dikirim ke " + emailInput);
                document.getElementById('reset-email').value = '';
            } else {
                showCustomToast("Harap masukkan alamat email Anda terlebih dahulu.", true);
            }
        }

        // --- PENGHITUNG KARAKTER REAL-TIME ---
        function updateCounter(inputId, counterId, max) {
            const input = document.getElementById(inputId);
            const counter = document.getElementById(counterId);
            const len = input.value.length;
            counter.innerText = `${len}/${max}`;
            
            if(len >= max) {
                counter.style.color = '#e74c3c'; // Warna merah jika kepenuhan
            } else {
                counter.style.color = '#555';
            }
        }

        // --- FUNGSI BUKA/TUTUP PASSWORD (DIPERBARUI) ---
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                iconElement.classList.replace("fa-eye", "fa-eye-slash");
                iconElement.style.color = "var(--gold)";
            } else {
                input.type = "password";
                iconElement.classList.replace("fa-eye-slash", "fa-eye");
                iconElement.style.color = "var(--text-muted)";
            }
        }

        // --- FUNGSI GANTI TAB ---
        function switchTab(tab, index) {
            const loginForm = document.getElementById('login-form');
            const regForm = document.getElementById('register-form');
            const indicator = document.getElementById('tab-indicator');
            const tabs = document.querySelectorAll('.tab');

            tabs.forEach(t => t.classList.remove('active'));
            tabs[index].classList.add('active');
            indicator.style.transform = `translateX(${index * 100}%)`;

            if(tab === 'login') {
                loginForm.style.display = 'block'; regForm.style.display = 'none';
            } else {
                loginForm.style.display = 'none'; regForm.style.display = 'block';
            }
        }

        // --- FUNGSI MODAL ---
        function openForgotModal() { document.getElementById('forgotModal').classList.add('active'); }
        function closeForgotModal() { document.getElementById('forgotModal').classList.remove('active'); }

        window.onclick = function(event) {
            const modal = document.getElementById('forgotModal');
            if (event.target == modal) { closeForgotModal(); }
        }

        // Tampilkan error/success bawaan Laravel menggunakan custom toast jika ada
        @if(session('error'))
            showCustomToast("{{ session('error') }}", true);
        @endif
        @if(session('success'))
            showCustomToast("{{ session('success') }}");
            switchTab('login', 0); // Otomatis pindah ke tab login jika berhasil daftar
        @endif
        // Jika ada error validasi dari register, buka tab register
        @if($errors->any())
            switchTab('register', 1);
            showCustomToast("{{ $errors->first() }}", true);
        @endif
    </script>
</body>
</html>