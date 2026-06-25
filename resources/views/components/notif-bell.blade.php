@auth
@php
    // Ambil notifikasi belum dibaca milik user yang login
    $unreadCount = \App\Models\Notification::where('user_id', Auth::id())
                    ->where('is_read', false)->count();
    $notifList   = \App\Models\Notification::where('user_id', Auth::id())
                    ->latest()->take(8)->get();
@endphp

{{-- ============================================================ --}}
{{-- KOMPONEN BELL NOTIFIKASI - Include ke navbar setiap halaman --}}
{{-- ============================================================ --}}
<style>
    /* === NOTIF BELL === */
    .notif-wrapper {
        position: relative;
        cursor: pointer;
    }
    .notif-bell-btn {
        background: none;
        border: none;
        color: var(--text-main, #f5f5f5);
        font-size: 16px;
        cursor: pointer;
        position: relative;
        padding: 5px;
        transition: color 0.3s;
        display: flex;
        align-items: center;
    }
    .notif-bell-btn:hover { color: var(--gold, #D4AF37); }

    .notif-badge {
        position: absolute;
        top: -4px; right: -8px;
        background: #E84C3D;
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        min-width: 16px;
        height: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        padding: 0 3px;
        animation: notif-pulse 1.5s infinite;
    }
    @keyframes notif-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(232, 76, 61, 0.5); }
        50%       { box-shadow: 0 0 0 6px rgba(232, 76, 61, 0); }
    }

    /* === DROPDOWN PANEL === */
    .notif-dropdown {
        position: absolute;
        top: calc(100% + 18px);
        right: -10px;
        width: 360px;
        background: var(--bg-surface, #1a1a1a);
        border: 1px solid var(--border-color, #2a2a2a);
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.6);
        z-index: 9999;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .notif-wrapper:hover .notif-dropdown,
    .notif-dropdown.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Header Panel */
    .notif-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 20px 14px;
        border-bottom: 1px solid var(--border-color, #2a2a2a);
    }
    .notif-header-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--text-main, #f5f5f5);
        letter-spacing: 1px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .notif-header-title i { color: var(--gold, #D4AF37); }
    .notif-baca-semua {
        font-size: 10px;
        color: var(--gold, #D4AF37);
        cursor: pointer;
        font-weight: 600;
        letter-spacing: 0.5px;
        background: none;
        border: none;
        padding: 0;
        transition: opacity 0.2s;
    }
    .notif-baca-semua:hover { opacity: 0.7; }

    /* List Item */
    .notif-list {
        max-height: 380px;
        overflow-y: auto;
        scrollbar-width: thin;
        scrollbar-color: var(--border-color, #2a2a2a) transparent;
    }
    .notif-list::-webkit-scrollbar { width: 4px; }
    .notif-list::-webkit-scrollbar-track { background: transparent; }
    .notif-list::-webkit-scrollbar-thumb { background: var(--border-color, #2a2a2a); border-radius: 4px; }

    .notif-item {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border-color, #2a2a2a);
        text-decoration: none;
        color: var(--text-main, #f5f5f5);
        transition: background 0.2s;
        position: relative;
        cursor: pointer;
    }
    .notif-item:last-child { border-bottom: none; }
    .notif-item:hover { background: rgba(212, 175, 55, 0.04); }
    .notif-item.unread { background: rgba(212, 175, 55, 0.03); }
    .notif-item.unread::before {
        content: '';
        position: absolute;
        left: 0; top: 0; bottom: 0;
        width: 3px;
        background: var(--gold, #D4AF37);
        border-radius: 0 2px 2px 0;
    }

    .notif-icon-wrap {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .notif-body { flex: 1; min-width: 0; }
    .notif-title {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-main, #f5f5f5);
        margin-bottom: 3px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .notif-msg {
        font-size: 11px;
        color: var(--text-muted, #888);
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .notif-time {
        font-size: 10px;
        color: var(--text-muted, #888);
        margin-top: 4px;
        display: block;
    }

    /* Empty State */
    .notif-empty {
        padding: 40px 20px;
        text-align: center;
        color: var(--text-muted, #888);
    }
    .notif-empty i {
        font-size: 36px;
        color: var(--border-color, #2a2a2a);
        margin-bottom: 12px;
        display: block;
    }
    .notif-empty p {
        font-size: 12px;
        line-height: 1.6;
    }

    /* Footer */
    .notif-footer {
        padding: 12px 20px;
        border-top: 1px solid var(--border-color, #2a2a2a);
        text-align: center;
    }
    .notif-footer a {
        font-size: 11px;
        color: var(--gold, #D4AF37);
        text-decoration: none;
        font-weight: 600;
        letter-spacing: 1px;
        transition: opacity 0.2s;
    }
    .notif-footer a:hover { opacity: 0.7; }

    /* Light mode support */
    body.light-mode .notif-dropdown {
        background: #ffffff;
        border-color: #e0e0e0;
        box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    }
    body.light-mode .notif-item:hover { background: rgba(212, 175, 55, 0.06); }
    body.light-mode .notif-item.unread { background: rgba(212, 175, 55, 0.05); }
</style>

<div class="notif-wrapper" id="notifWrapper">
    {{-- Tombol Bell --}}
    <button class="notif-bell-btn" id="notifBellBtn" onclick="toggleNotifPanel(event)" aria-label="Notifikasi">
        <i class="fas fa-bell"></i>
        @if($unreadCount > 0)
            <span class="notif-badge" id="notifBadge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
        @else
            <span class="notif-badge" id="notifBadge" style="display:none;">0</span>
        @endif
    </button>

    {{-- Panel Dropdown --}}
    <div class="notif-dropdown" id="notifDropdown">
        {{-- Header --}}
        <div class="notif-header">
            <span class="notif-header-title">
                <i class="fas fa-bell"></i> Notifikasi
                @if($unreadCount > 0)
                    <span style="background: #E84C3D; color: #fff; font-size: 9px; padding: 2px 6px; border-radius: 10px;">{{ $unreadCount }} baru</span>
                @endif
            </span>
            @if($notifList->count() > 0)
                <button class="notif-baca-semua" onclick="bacaSemua()">
                    <i class="fas fa-check-double" style="margin-right: 4px;"></i>Tandai Semua Dibaca
                </button>
            @endif
        </div>

        {{-- List Notifikasi --}}
        <div class="notif-list" id="notifList">
            @forelse($notifList as $notif)
                <div class="notif-item {{ !$notif->is_read ? 'unread' : '' }}"
                     id="notif-{{ $notif->id }}"
                     onclick="klikNotif({{ $notif->id }}, '{{ $notif->url }}')">
                    {{-- Ikon --}}
                    <div class="notif-icon-wrap"
                         style="background: {{ $notif->color }}18; border: 1px solid {{ $notif->color }}30;">
                        <i class="fas {{ $notif->icon }}" style="color: {{ $notif->color }};"></i>
                    </div>
                    {{-- Konten --}}
                    <div class="notif-body">
                        <div class="notif-title">{{ $notif->title }}</div>
                        <div class="notif-msg">{{ $notif->message }}</div>
                        <span class="notif-time">
                            <i class="far fa-clock" style="margin-right: 3px;"></i>
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="notif-empty">
                    <i class="far fa-bell-slash"></i>
                    <p>Belum ada notifikasi.<br>Kami akan memberitahu kamu di sini!</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        @if($notifList->count() > 0)
        <div class="notif-footer">
            <a href="{{ url('/notifikasi') }}">Lihat Semua Notifikasi <i class="fas fa-arrow-right" style="margin-left: 5px;"></i></a>
        </div>
        @endif
    </div>
</div>

<script>
    // Toggle panel notif saat klik bell
    function toggleNotifPanel(e) {
        if (e) e.stopPropagation();
        const panel = document.getElementById('notifDropdown');
        panel.classList.toggle('open');
    }

    // Tutup panel saat klik di luar
    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('notifWrapper');
        if (wrapper && !wrapper.contains(e.target)) {
            const panel = document.getElementById('notifDropdown');
            if (panel) panel.classList.remove('open');
        }
    });

    // Klik satu notifikasi → tandai baca lalu navigasi
    function klikNotif(id, url) {
        fetch(`/notifikasi/baca/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(() => {
            // Hapus style "unread" dari item ini
            const item = document.getElementById('notif-' + id);
            if (item) item.classList.remove('unread');
            // Update badge count
            updateNotifBadge();
            // Navigasi ke URL jika ada
            if (url && url !== 'null' && url !== '') {
                window.location.href = url;
            }
        });
    }

    // Tandai semua notif sebagai sudah dibaca
    function bacaSemua() {
        fetch('/notifikasi/baca-semua', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(() => {
            // Hapus semua class unread
            document.querySelectorAll('.notif-item.unread').forEach(el => {
                el.classList.remove('unread');
            });
            // Sembunyikan badge
            const badge = document.getElementById('notifBadge');
            if (badge) badge.style.display = 'none';
        });
    }

    // Update badge angka berdasarkan item unread yang tersisa
    function updateNotifBadge() {
        const badge = document.getElementById('notifBadge');
        if (!badge) return;
        const unreadItems = document.querySelectorAll('.notif-item.unread').length;
        if (unreadItems <= 0) {
            badge.style.display = 'none';
        } else {
            badge.style.display = 'flex';
            badge.innerText = unreadItems > 9 ? '9+' : unreadItems;
        }
    }
</script>
@endauth
