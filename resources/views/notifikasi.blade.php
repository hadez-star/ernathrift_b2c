@php
    $setting = \App\Models\WebSetting::first();
    $nama_toko = $setting->nama_toko ?? 'ERNA THRIFTING';
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Notifikasi | {{ $nama_toko }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            --unread-bg: rgba(212, 175, 55, 0.05);
        }

        body.light-mode {
            --bg-dark: #f5f5f5;
            --bg-surface: #ffffff;
            --text-main: #111111;
            --text-muted: #666666;
            --border-color: #dddddd;
            --unread-bg: rgba(212, 175, 55, 0.08);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            min-height: 100vh;
            padding: 40px 20px;
            transition: background-color 0.4s ease, color 0.4s ease;
        }

        body:not(.light-mode) { background: radial-gradient(circle at top center, #1f1a14 0%, var(--bg-dark) 100%); }
        body.light-mode { background: radial-gradient(circle at top center, #ffffff 0%, var(--bg-dark) 100%); }

        .container { max-width: 800px; margin: 0 auto; animation: eleganceIn 0.8s ease forwards; }

        @keyframes eleganceIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* --- HEADER --- */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .btn-back { color: var(--text-muted); text-decoration: none; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px; transition: 0.3s; text-transform: uppercase; letter-spacing: 1px; }
        .btn-back:hover { color: var(--gold); transform: translateX(-5px); }
        .header h1 { font-family: 'Playfair Display', serif; font-size: 32px; color: var(--gold); margin: 0; }

        /* --- NOTIFICATION LIST --- */
        .notif-list { background: var(--bg-surface); border: 1px solid var(--border-color); border-radius: 16px; overflow: hidden; box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        body.light-mode .notif-list { box-shadow: 0 20px 40px rgba(0,0,0,0.05); }

        .notif-item { padding: 25px 30px; border-bottom: 1px solid var(--border-color); display: flex; gap: 20px; text-decoration: none; color: inherit; transition: 0.3s; position: relative; }
        .notif-item:last-child { border-bottom: none; }
        .notif-item.unread { background: var(--unread-bg); }
        .notif-item.unread::before { content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 4px; background: var(--gold); }
        .notif-item:hover { background: rgba(136, 136, 136, 0.05); }

        .notif-icon { width: 50px; height: 50px; border-radius: 50%; background: rgba(212, 175, 55, 0.1); color: var(--gold); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .notif-item.unread .notif-icon { background: var(--gold); color: #fff; }

        .notif-content { flex: 1; }
        .notif-title { font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 5px; }
        .notif-message { font-size: 13px; color: var(--text-muted); line-height: 1.6; margin-bottom: 10px; }
        .notif-time { font-size: 11px; color: var(--text-muted); display: flex; align-items: center; gap: 5px; opacity: 0.7; }

        .empty-state { text-align: center; padding: 100px 20px; }
        .empty-state i { font-size: 60px; color: var(--gold); opacity: 0.3; margin-bottom: 25px; }
        .empty-state h2 { font-family: 'Playfair Display', serif; font-size: 28px; color: var(--gold); margin-bottom: 10px; }
        .empty-state p { color: var(--text-muted); font-size: 14px; }

        .btn-mark-all { font-size: 11px; font-weight: 700; color: var(--gold); text-transform: uppercase; letter-spacing: 1px; cursor: pointer; background: none; border: none; padding: 10px; transition: 0.3s; }
        .btn-mark-all:hover { opacity: 0.7; transform: translateY(-2px); }

        @media (max-width: 600px) {
            .header { flex-direction: column; align-items: flex-start; gap: 20px; }
            .notif-item { padding: 20px; }
            .notif-icon { width: 40px; height: 40px; font-size: 16px; }
        }
    </style>
</head>
<body>

    <!-- SCRIPT PENGINGAT TEMA -->
    <script>
        if (localStorage.getItem('erna_theme') === 'light') {
            document.body.classList.add('light-mode');
        }
    </script>

    <div class="container">
        <div class="header">
            <a href="javascript:history.back()" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali</a>
            <h1>Notifikasi</h1>
            @if($notifications->count() > 0)
            <form action="{{ url('/notifikasi/mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn-mark-all">Tandai Semua Dibaca</button>
            </form>
            @endif
        </div>

        @if($notifications->count() > 0)
        <div class="notif-list">
            @foreach($notifications as $notif)
            <a href="{{ $notif->url ?? '#' }}" class="notif-item {{ !$notif->is_read ? 'unread' : '' }}">
                <div class="notif-icon">
                    @php
                        $icon = 'fa-bell';
                        if(strpos(strtolower($notif->title), 'pesanan') !== false) $icon = 'fa-shopping-bag';
                        if(strpos(strtolower($notif->title), 'voucher') !== false) $icon = 'fa-ticket-alt';
                        if(strpos(strtolower($notif->title), 'flash') !== false) $icon = 'fa-bolt';
                        if(strpos(strtolower($notif->title), 'retur') !== false) $icon = 'fa-undo';
                    @endphp
                    <i class="fas {{ $icon }}"></i>
                </div>
                <div class="notif-content">
                    <div class="notif-title">{{ $notif->title }}</div>
                    <div class="notif-message">{{ $notif->message }}</div>
                    <div class="notif-time"><i class="far fa-clock"></i> {{ $notif->created_at->diffForHumans() }}</div>
                </div>
            </a>
            @endforeach
        </div>
        
        <div style="margin-top: 30px; display: flex; justify-content: center;">
            {{ $notifications->links() }}
        </div>
        @else
        <div class="notif-list">
            <div class="empty-state">
                <i class="far fa-bell-slash"></i>
                <h2>Belum Ada Notifikasi</h2>
                <p>Semua pembaruan penting tentang pesanan dan promo akan muncul di sini.</p>
            </div>
        </div>
        @endif
    </div>

</body>
</html>
