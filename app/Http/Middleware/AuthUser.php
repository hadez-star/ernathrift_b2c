<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthUser
{
    /**
     * Handle an incoming request.
     * Pastikan user sudah login sebelum mengakses halaman yang memerlukan autentikasi.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['status' => 'login_required', 'message' => 'Silakan login terlebih dahulu.'], 200);
            }
            return redirect('/login')->with('error', 'Silakan login terlebih dahulu untuk melanjutkan.');
        }

        // Cek dan kelola kedaluwarsa membership secara real-time
        $user = Auth::user();
        if ($user->vip_paket !== 'REGULER' && $user->member_until && \Carbon\Carbon::parse($user->member_until)->isPast()) {
            $paketSebelumnya = $user->vip_paket;
            $user->update([
                'vip_paket' => 'REGULER',
                'member_until' => null,
            ]);
            
            \App\Models\Notification::kirim($user->id, [
                'type' => 'system',
                'title' => 'Membership VIP Berakhir',
                'message' => "Masa aktif membership VIP {$paketSebelumnya} Anda telah habis. Status Anda telah kembali menjadi REGULER.",
                'url' => url('/membership-vip'),
                'icon' => 'fa-exclamation-circle',
                'color' => '#E84C3D',
            ]);
        }

        return $next($request);
    }
}
