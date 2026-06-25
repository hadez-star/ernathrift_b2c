<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     * Hanya user yang sudah login dan memiliki role 'admin' yang bisa masuk.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login terlebih dahulu sebagai Administrator.');
        }

        // Cek apakah user memiliki role admin
        if (Auth::user()->role !== 'admin') {
            // Jika bukan admin, tolak akses dan kembalikan ke halaman utama
            abort(403, 'Akses ditolak. Halaman ini hanya untuk Administrator.');
        }

        return $next($request);
    }
}
