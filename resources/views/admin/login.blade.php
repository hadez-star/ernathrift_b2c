@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | {{ $nama_toko }}</title>
    
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
            flex: 1.2;
            background: linear-gradient(rgba(15, 15, 15, 0.7), rgba(15, 15, 15, 0.95)), 
                        url('https://images.unsplash.com/photo-1490481651871-ab68de25d43d?q=80&w=2070&auto=format&fit=crop') center/cover no-repeat;
            display: flex; flex-direction: column; justify-content: center; padding: 80px; position: relative;
        }
        .brand-side::before {
            content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at center, transparent 0%, var(--bg-dark) 100%);
        }
        .brand-content { position: relative; z-index: 1; max-width: 500px; }
        .brand-title { 
            font-family: 'Playfair Display', serif; 
            font-size: 56px; 
            font-weight: 700; 
            color: var(--gold); 
            line-height: 1.1; 
            margin-bottom: 20px; 
            letter-spacing: -1px; 
        }
        .brand-tagline { 
            font-size: 14px; 
            color: var(--text-main); 
            line-height: 1.8; 
            font-weight: 300; 
            letter-spacing: 1px; 
        }

        .form-side { 
            flex: 1; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            padding: 40px; 
            background-color: var(--bg-dark); 
            position: relative; 
        }
        .form-container { 
            width: 100%; 
            max-width: 400px; 
            animation: fadeInRight 0.8s ease-out forwards; 
        }

        @keyframes fadeInRight { 
            from { opacity: 0; transform: translateX(30px); } 
            to { opacity: 1; transform: translateX(0); } 
        }

        .form-header {
            margin-bottom: 40px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 20px;
        }

        .form-header i {
            font-size: 30px;
            color: var(--gold);
            margin-bottom: 15px;
        }

        .form-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: var(--text-main);
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .form-header p {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.6;
            font-weight: 300;
        }

        /* --- FLOATING LABEL INPUTS --- */
        .floating-group { position: relative; margin-bottom: 30px; }
        .floating-input {
            width: 100%; 
            padding: 20px 40px 10px 0; 
            font-size: 15px; 
            color: var(--text-main); 
            background: transparent;
            border: none; 
            border-bottom: 1px solid var(--border-color); 
            outline: none; 
            transition: border-color 0.3s; 
            font-family: 'Montserrat', sans-serif;
        }
        .floating-label { 
            position: absolute; 
            top: 15px; 
            left: 0; 
            font-size: 14px; 
            color: var(--text-muted); 
            transition: all 0.3s ease; 
            pointer-events: none; 
        }
        .floating-input:focus, .floating-input:not(:placeholder-shown) { 
            border-bottom-color: var(--gold); 
        }
        .floating-input:focus ~ .floating-label, .floating-input:not(:placeholder-shown) ~ .floating-label { 
            top: -5px; 
            font-size: 11px; 
            color: var(--gold); 
            letter-spacing: 1px; 
            text-transform: uppercase; 
        }
        
        .password-toggle { 
            position: absolute; 
            right: -10px; 
            top: 10px; 
            cursor: pointer; 
            color: var(--text-muted); 
            transition: 0.3s; 
            z-index: 10; 
            padding: 10px; 
        }
        .password-toggle:hover { color: var(--gold); }

        /* --- OPSI & TOMBOL --- */
        .options { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 40px; 
            margin-top: 10px; 
        }
        .checkbox-container { 
            display: flex; 
            align-items: center; 
            cursor: pointer; 
            font-size: 12px; 
            color: var(--text-muted); 
            user-select: none; 
        }
        .checkbox-container input { position: absolute; opacity: 0; cursor: pointer; height: 0; width: 0; }
        .checkmark { 
            height: 16px; 
            width: 16px; 
            background-color: transparent; 
            border: 1px solid var(--border-color); 
            border-radius: 3px; 
            margin-right: 10px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            transition: 0.2s; 
        }
        .checkbox-container input:checked ~ .checkmark { 
            background-color: var(--gold); 
            border-color: var(--gold); 
        }
        .checkmark:after { 
            content: "\f00c"; 
            font-family: "Font Awesome 5 Free"; 
            font-weight: 900; 
            font-size: 10px; 
            color: var(--bg-dark); 
            display: none; 
        }
        .checkbox-container input:checked ~ .checkmark:after { display: block; }

        .btn-submit {
            width: 100%; 
            padding: 16px; 
            background-color: var(--gold); 
            color: var(--bg-dark); 
            border: none; 
            font-family: 'Montserrat', sans-serif;
            font-size: 13px; 
            font-weight: 600; 
            text-transform: uppercase; 
            letter-spacing: 2px; 
            cursor: pointer; 
            transition: background 0.3s, transform 0.3s;
            display: flex; 
            justify-content: center; 
            align-items: center; 
            gap: 10px; 
            margin-top: 15px;
        }
        .btn-submit:hover { 
            background-color: var(--gold-hover); 
        }

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

        @media (max-width: 768px) {
            .brand-side { display: none; }
            .form-side { padding: 30px; }
            .form-container { max-width: 100%; }
            .mobile-brand { display: block; text-align: left; margin-bottom: 40px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px; }
            .mobile-brand h1 { font-family: 'Playfair Display', serif; color: var(--gold); font-size: 32px; letter-spacing: 1px; }
            .mobile-brand p { font-size: 10px; color: var(--text-muted); letter-spacing: 3px; text-transform: uppercase; margin-top: 5px; }
            .form-header { display: none; }
            .custom-toast { top: 20px; right: auto; left: 50%; transform: translateX(-50%); width: 90%; transition: top 0.5s ease; top: -100px; }
            .custom-toast.show { top: 20px; right: auto; }
        }
        @media (min-width: 769px) { .mobile-brand { display: none; } }
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
                <h1 class="brand-title">ERNA.<br>Admin Portal.</h1>
                <p class="brand-tagline">Pusat kendali eksklusif untuk manajemen toko, pemantauan performa, dan kurasi inventaris premium ERNA Thrifting.</p>
            </div>
        </div>

        <div class="form-side">
            <div class="form-container">
                
                <div class="mobile-brand">
                    <h1>ERNA.</h1>
                    <p>Admin Portal</p>
                </div>

                <div class="form-header">
                    <i class="fa-solid fa-user-shield"></i>
                    <h2>Masuk Admin</h2>
                    <p>Otentikasi diperlukan. Masukkan kredensial administrator Anda untuk melanjutkan.</p>
                </div>

                <form action="{{ route('admin.login.process') }}" method="POST" id="login-form">
                    @csrf
                    <div class="floating-group">
                        <input type="email" name="email" id="email" class="floating-input" placeholder=" " required autofocus>
                        <label for="email" class="floating-label">Alamat Email</label>
                    </div>

                    <div class="floating-group">
                        <input type="password" name="password" id="password" class="floating-input" placeholder=" " required>
                        <label for="password" class="floating-label">Kata Sandi</label>
                        <i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                    </div>
                    
                    <div class="options">
                        <label class="checkbox-container">
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Ingat Sesi Saya
                        </label>
                    </div>

                    <button type="submit" class="btn-submit">
                        Masuk Dashboard <i class="fas fa-arrow-right"></i>
                    </button>
                </form>
            </div>
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

        // --- FUNGSI BUKA/TUTUP PASSWORD ---
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

        @if(session('error'))
            showCustomToast("{{ session('error') }}", true);
        @endif
        
        @if(session('success'))
            showCustomToast("{{ session('success') }}");
        @endif
    </script>
</body>
</html>
