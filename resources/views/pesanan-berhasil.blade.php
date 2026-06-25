<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - ERNA Thrifting</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #fcfaf8; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .success-card { background: white; padding: 50px 40px; border-radius: 20px; text-align: center; max-width: 450px; width: 90%; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        
        .icon-check { width: 80px; height: 80px; background: #8c6b5d; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin: 0 auto 30px; }
        
        h1 { font-family: 'Playfair Display', serif; font-size: 32px; color: #333; margin-bottom: 15px; }
        p { color: #777; font-size: 14px; line-height: 1.6; margin-bottom: 30px; }
        
        .info-box { background: #faf9f7; border-radius: 12px; padding: 20px; text-align: left; margin-bottom: 35px; border: 1px solid #eee; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .info-label { color: #888; }
        .info-value { font-weight: 600; color: #333; }
        
        .btn-continue { display: block; background: #333; color: white; text-decoration: none; padding: 16px; border-radius: 8px; font-weight: 700; font-size: 13px; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .btn-continue:hover { background: #000; transform: translateY(-2px); }
    </style>
</head>
<body>

    <div class="success-card">
        <div class="icon-check"><i class="fas fa-check"></i></div>
        <h1>Pesanan Dibuat!</h1>
        <p>Terima kasih telah berbelanja. Detail pesanan Anda telah dikirim ke email.</p>
        
        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Metode:</span>
                <span class="info-value">QRIS / ERNA Pay</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value" style="color: #f39c12;">Menunggu Konfirmasi</span>
            </div>
            <hr style="border: none; border-top: 1px solid #eee; margin: 15px 0;">
            <div class="info-row" style="font-size: 16px;">
                <span class="info-label" style="font-weight: 700; color: #333;">Total Dibayar:</span>
                <span class="info-value" style="color: #8c6b5d; font-weight: 800;">Rp 200.000</span>
            </div>
        </div>

        <a href="/" class="btn-continue">LANJUT BELANJA</a>
    </div>

</body>
</html>