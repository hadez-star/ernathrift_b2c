<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: #0f0f0f;
            color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }
        .bg-glow {
            position: fixed;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(212,175,55,0.06) 0%, transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }
        .container {
            text-align: center;
            padding: 40px 20px;
            animation: fadeIn 0.8s ease-out;
            position: relative;
            z-index: 1;
        }
        @keyframes fadeIn { from { opacity:0; transform: translateY(30px); } to { opacity:1; transform: translateY(0); } }

        .error-icon {
            font-size: 80px;
            color: #D4AF37;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%,100% { opacity:1; } 50% { opacity:0.6; } }

        .error-code {
            font-family: 'Playfair Display', serif;
            font-size: 120px;
            font-weight: 700;
            color: #D4AF37;
            line-height: 1;
            letter-spacing: -5px;
            margin-bottom: 10px;
        }
        .error-title {
            font-size: 22px;
            font-weight: 600;
            color: #f5f5f5;
            margin-bottom: 15px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .error-desc {
            font-size: 14px;
            color: #888;
            margin-bottom: 40px;
            line-height: 1.7;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }
        .divider {
            width: 60px; height: 2px;
            background: linear-gradient(to right, transparent, #D4AF37, transparent);
            margin: 20px auto 30px;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: #D4AF37;
            color: #0f0f0f;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-decoration: none;
            transition: all 0.3s;
            margin: 0 8px;
        }
        .btn-back:hover { background: #bda038; transform: translateY(-2px); }
        .btn-outline {
            background: transparent;
            border: 1px solid #333;
            color: #888;
        }
        .btn-outline:hover { border-color: #D4AF37; color: #D4AF37; background: transparent; }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="container">
        <div class="error-icon"><i class="fas fa-shield-alt"></i></div>
        <div class="error-code">403</div>
        <div class="divider"></div>
        <h1 class="error-title">Akses Ditolak</h1>
        <p class="error-desc">
            Anda tidak memiliki izin untuk mengakses halaman ini.<br>
            Halaman admin hanya dapat diakses oleh Administrator.
        </p>
        <a href="/" class="btn-back"><i class="fas fa-home"></i> Ke Beranda</a>
        <a href="/login" class="btn-back btn-outline"><i class="fas fa-sign-in-alt"></i> Login Admin</a>
    </div>
</body>
</html>
