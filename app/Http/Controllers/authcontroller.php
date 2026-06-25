<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Mesin Pendaftaran
    public function register(Request $request)
    {
        // 1. Cek apakah data lengkap dan valid
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // 2. Simpan ke database MySQL
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Sandi dienkripsi agar aman!
            'role' => 'user',
            'status' => 'Aktif',
            'vip_tier' => 'Reguler',
            'balance' => 0,
        ]);

        // 3. Langsung loginkan pengguna setelah mendaftar
        Auth::login($user);

        return redirect('/')->with('success', 'Pendaftaran Berhasil! Selamat datang.');
    }

    // Mesin Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Jika email dan password cocok dengan database...
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Cek apakah yang login adalah Admin?
            if(Auth::user()->role === 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Selamat datang kembali, Admin!');
            }
            
            // Jika pelanggan biasa, kembalikan ke halaman utama
            return redirect('/')->with('success', 'Login Berhasil!');
        }

        // Jika salah password/email
        return back()->with('error', 'Email atau Password salah.');
    }

    // Mesin Keluar (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Anda berhasil keluar.');
    }
}