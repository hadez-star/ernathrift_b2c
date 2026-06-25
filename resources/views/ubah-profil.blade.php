@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
    $user = Auth::user();
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil | {{ $nama_toko }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --gold: #D4AF37;
            --gold-hover: #bda038;
            --bg-dark: #0f0f0f;
            --bg-surface: #1a1a1a;
            --text-main: #f5f5f5;
            --text-muted: #888888;
            --border-color: #2a2a2a;
        }

        /* --- TEMA TERANG (LIGHT MODE) GLOBAL --- */
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            transition: background-color 0.4s ease, color 0.4s ease;
        }
        
        body:not(.light-mode) { background: radial-gradient(circle at center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at center, #ffffff 0%, #f0f0f0 100%); }

        .settings-container {
            width: 100%;
            max-width: 1000px;
            animation: eleganceIn 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        @keyframes eleganceIn { to { transform: translateY(0); opacity: 1; } }

        .settings-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 50px;
            position: relative;
            transition: 0.4s ease;
        }
        
        body:not(.light-mode) .settings-card { box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), inset 0 0 20px rgba(212, 175, 55, 0.02); border-color: rgba(212, 175, 55, 0.15); }
        body.light-mode .settings-card { box-shadow: 0 20px 50px rgba(0,0,0,0.05); }

        /* --- HEADER --- */
        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 40px;
            padding-bottom: 25px;
            border-bottom: 1px solid var(--border-color);
            transition: 0.4s ease;
        }

        .btn-back {
            color: var(--text-muted);
            font-size: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(136, 136, 136, 0.05);
            border: 1px solid var(--border-color);
            margin-right: 20px;
        }
        .btn-back:hover { color: var(--gold); background: rgba(212, 175, 55, 0.1); transform: translateX(-5px); border-color: var(--gold); }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--gold);
            letter-spacing: 1px;
        }

        /* --- LAYOUT GRID --- */
        .form-layout { display: grid; grid-template-columns: 1fr 1.5fr; gap: 50px; }
        .col-left { border-right: 1px solid var(--border-color); padding-right: 50px; transition: 0.4s ease;}

        /* --- PHOTO UPLOAD SECTION --- */
        .avatar-section { text-align: center; margin-bottom: 35px; }
        
        .avatar-preview-wrap {
            width: 140px; height: 140px; margin: 0 auto 20px;
            border-radius: 50%; border: 3px solid var(--gold);
            padding: 5px; position: relative; 
            display: flex; align-items: center; justify-content: center;
            background: var(--border-color); overflow: hidden; transition: 0.4s ease;
        }
        body:not(.light-mode) .avatar-preview-wrap { box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
        
        .avatar-preview-wrap img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block; }
        .avatar-preview-wrap i { font-size: 50px; color: var(--text-muted); }

        .btn-change-photo {
            display: inline-flex; align-items: center; gap: 8px; padding: 8px 20px;
            background: rgba(136, 136, 136, 0.05); border: 1px solid var(--border-color); color: var(--text-main);
            border-radius: 30px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;
            cursor: pointer; transition: 0.3s;
        }
        .btn-change-photo:hover { background: rgba(212, 175, 55, 0.1); color: var(--gold); border-color: var(--gold); }
        .btn-change-photo input { display: none; }

        /* --- FORM INPUTS --- */
        .section-title { font-size: 11px; color: var(--gold); text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px; border-left: 2px solid var(--gold); padding-left: 10px; font-weight: 600; }
        .input-group { position: relative; margin-bottom: 25px; }
        .input-label { display: block; font-size: 11px; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px; font-weight: 500; transition: 0.4s ease;}
        
        .input-icon { position: absolute; left: 15px; top: 42px; color: var(--text-muted); transition: 0.3s; }
        .input-control { width: 100%; background: transparent; border: 1px solid var(--border-color); color: var(--text-main); padding: 14px 15px 14px 45px; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 13px; transition: all 0.3s ease; }
        .input-control:focus { outline: none; border-color: var(--gold); background: rgba(212, 175, 55, 0.02); }
        .input-control:focus ~ .input-icon { color: var(--gold); }
        textarea.input-control { padding-top: 14px; min-height: 100px; resize: vertical; }

        .helper-text { display: block; margin-top: 8px; font-size: 10px; color: var(--text-muted); transition: 0.4s ease;}
        .row-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

        .btn-submit { width: 100%; padding: 16px; background: var(--gold); color: #111; border: none; border-radius: 8px; font-family: 'Montserrat', sans-serif; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: 0.3s; margin-top: 15px; }
        .btn-submit:hover { background: var(--gold-hover); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(212, 175, 55, 0.2); }

        /* --- MODAL CROPPER --- */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); align-items: center; justify-content: center; backdrop-filter: blur(5px); }
        .modal-content { background-color: var(--bg-surface); border: 1px solid var(--border-color); width: 90%; max-width: 400px; border-radius: 16px; padding: 25px; text-align: center; transition: 0.4s ease;}
        body:not(.light-mode) .modal-content { box-shadow: 0 20px 50px rgba(0,0,0,0.8); }
        body.light-mode .modal-content { box-shadow: 0 15px 40px rgba(0,0,0,0.1); }
        
        .modal-content h3 { color: var(--gold); font-family: 'Playfair Display', serif; font-size: 20px; margin-bottom: 20px; }
        
        .img-container { width: 100%; max-height: 350px; margin-bottom: 20px; background: #000; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); }
        .img-container img { max-width: 100%; display: block; }
        
        .modal-btns { display: flex; gap: 10px; justify-content: center; }
        .btn-crop { background-color: var(--gold); color: #111; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; flex: 1; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; transition: 0.3s;}
        .btn-cancel { background-color: transparent; color: var(--text-main); border: 1px solid var(--border-color); padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; flex: 1; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; transition: 0.3s;}
        
        body:not(.light-mode) .btn-cancel:hover { background: rgba(255,255,255,0.05); }
        body.light-mode .btn-cancel:hover { background: rgba(0,0,0,0.05); }

        @media (max-width: 850px) {
            .form-layout { grid-template-columns: 1fr; gap: 40px; }
            .col-left { border-right: none; padding-right: 0; border-bottom: 1px solid var(--border-color); padding-bottom: 40px; }
            .settings-card { padding: 30px; }
        }

        /* --- TOAST --- */
        .ecommerce-toast { border-radius: 12px !important; padding: 10px 20px !important; box-shadow: 0 15px 35px rgba(0,0,0,0.8) !important; background: var(--bg-surface) !important; color: var(--text-main) !important; border: 1px solid var(--border-color) !important; }
        body.light-mode .ecommerce-toast { box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important; }
    </style>
</head>
<body>

    <!-- SCRIPT PENGINGAT TEMA -->
    <script>
        if (localStorage.getItem('erna_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>
    <!-- ======================= -->

    <div class="settings-container">
        <form action="{{ url('/ubah-profil') }}" method="POST" enctype="multipart/form-data" class="settings-card">
            @csrf

            <input type="hidden" name="image_base64" id="image_base64">

            <div class="card-header">
                <a href="{{ url('/profile') }}" class="btn-back"><i class="fas fa-arrow-left"></i></a>
                <h1 class="page-title">Pengaturan Profil</h1>
            </div>

            <div class="form-layout">
                
                <div class="col-left">
                    <div class="avatar-section">
                        <div class="avatar-preview-wrap">
                            @if(isset($user->foto) && $user->foto)
                                <img src="{{ asset('uploads/profile/' . $user->foto) }}" id="imagePreview" alt="Foto Profil">
                                <i class="fas fa-user" id="iconPreview" style="display:none;"></i>
                            @else
                                <img src="" id="imagePreview" style="display:none;" alt="Foto Profil">
                                <i class="fas fa-user" id="iconPreview"></i>
                            @endif
                        </div>
                        
                        <label class="btn-change-photo">
                            <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg">
                            <i class="fas fa-camera"></i> Ganti Foto
                        </label>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Nama Lengkap</label>
                        <input type="text" name="name" class="input-control" value="{{ old('name', $user->name ?? '') }}" required>
                        <i class="far fa-user input-icon"></i>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Alamat Email</label>
                        <input type="email" name="email" class="input-control" value="{{ old('email', $user->email ?? '') }}" required>
                        <i class="far fa-envelope input-icon"></i>
                    </div>

                    <div class="input-group">
                        <label class="input-label">Nomor HP / WA</label>
                        <input type="text" name="no_hp" class="input-control" value="{{ old('no_hp', $user->no_hp ?? '') }}" placeholder="08xxxxxxxxxx">
                        <i class="fas fa-phone-alt input-icon"></i>
                    </div>
                </div>

                <div class="col-right">
                    <h3 class="section-title">Informasi Alamat</h3>

                    <div class="input-group">
                        <label class="input-label">Alamat Lengkap Pengiriman</label>
                        <textarea name="alamat" class="input-control" placeholder="Nama Jalan, RT/RW, Kelurahan, Kecamatan, Kota">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                        <i class="fas fa-map-marker-alt input-icon" style="top: 38px;"></i>
                    </div>

                    <div class="row-grid">
                        <div class="input-group">
                            <label class="input-label">No. Rumah / Blok</label>
                            <input type="text" name="no_rumah" class="input-control" value="{{ old('no_rumah', $user->no_rumah ?? '') }}" placeholder="Mis: A1">
                            <i class="fas fa-home input-icon"></i>
                        </div>
                        <div class="input-group">
                            <label class="input-label">Kode Pos</label>
                            <input type="text" name="kode_pos" class="input-control" value="{{ old('kode_pos', $user->kode_pos ?? '') }}" placeholder="Kodepos">
                            <i class="fas fa-mail-bulk input-icon"></i>
                        </div>
                    </div>

                    <h3 class="section-title" style="margin-top: 15px;">Keamanan Akun</h3>

                    <div class="input-group">
                        <label class="input-label">Kata Sandi Baru</label>
                        <input type="password" name="password" class="input-control" placeholder="Isi jika ingin ganti sandi">
                        <i class="fas fa-lock input-icon"></i>
                        <span class="helper-text">*Kosongkan kotak ini jika Anda tidak ingin mengubah kata sandi.</span>
                    </div>

                    <button type="submit" class="btn-submit">
                        Simpan Perubahan Profil
                    </button>
                </div>

            </div>
        </form>
    </div>

    <div id="cropModal" class="modal">
        <div class="modal-content">
            <h3>Sesuaikan Foto Anda</h3>
            <div class="img-container">
                <img id="imageToCrop" src="">
            </div>
            <div class="modal-btns">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="button" class="btn-crop" onclick="cropImage()">Potong & Pakai</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script>
        let cropper;
        const imageUpload = document.getElementById('imageUpload');
        const imageToCrop = document.getElementById('imageToCrop');
        const cropModal = document.getElementById('cropModal');
        const imagePreview = document.getElementById('imagePreview');
        const iconPreview = document.getElementById('iconPreview');
        const imageBase64 = document.getElementById('image_base64');

        imageUpload.addEventListener('change', function(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    imageToCrop.src = event.target.result;
                    cropModal.style.display = 'flex';
                    if (cropper) { cropper.destroy(); }
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1, viewMode: 1, dragMode: 'move', autoCropArea: 1
                    });
                };
                reader.readAsDataURL(files[0]);
            }
        });

        function closeModal() {
            cropModal.style.display = 'none';
            if (cropper) { cropper.destroy(); }
            imageUpload.value = '';
        }

        function cropImage() {
            if (!cropper) return;
            const canvas = cropper.getCroppedCanvas({ width: 400, height: 400 });
            const base64Image = canvas.toDataURL('image/png');
            imagePreview.src = base64Image;
            imagePreview.style.display = 'block';
            if(iconPreview) iconPreview.style.display = 'none';
            imageBase64.value = base64Image;
            closeModal();
        }

        // Fungsi Warna SweetAlert Berdasarkan Tema
        function getSwalColors() {
            return {
                bg: document.body.classList.contains('light-mode') ? '#ffffff' : '#1a1a1a',
                text: document.body.classList.contains('light-mode') ? '#111111' : '#ffffff'
            };
        }
        
        let c = getSwalColors();

        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
            background: c.bg, color: c.text, customClass: { popup: 'ecommerce-toast' }
        });

        @if(session('success')) Toast.fire({ icon: 'success', title: "{{ session('success') }}", iconColor: '#D4AF37' }); @endif
        @if($errors->any()) Toast.fire({ icon: 'error', title: "{{ $errors->first() }}", iconColor: '#e74c3c' }); @endif
    </script>
</body>
</html>