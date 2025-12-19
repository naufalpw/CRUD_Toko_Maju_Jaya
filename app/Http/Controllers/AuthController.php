<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // 1. Tampilkan Form Login
    public function showLoginForm()
    {
        // PERBAIKAN: Menggunakan response() dengan header anti-cache
        // Ini mencegah browser menyimpan halaman login lama yang token-nya sudah kadaluarsa
        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    // 2. Proses Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('dashboard.index')); 
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Menghapus session lama
        $request->session()->invalidate();
        
        // Membuat token baru (Penting untuk mencegah 419 di login berikutnya)
        $request->session()->regenerateToken();
        
        // Redirect ke route login
        return redirect()->route('login');
    }
}