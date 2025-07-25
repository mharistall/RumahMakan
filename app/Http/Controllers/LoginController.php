<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLogin() {
        return view('login');
    }

    public function login(Request $request)
    {
        // Perubahan di sini: Menggunakan 'username' alih-alih 'email'
        // Sesuai dengan Class Diagram Anda yang menggunakan Username untuk login.
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            // Penting: Regenerate session untuk keamanan setelah login berhasil
            $request->session()->regenerate();

            $role = auth()->user()->role;
            if ($role === 'pemilik') {
                // Menggunakan intended() akan mengarahkan user ke URL yang ingin diakses
                // sebelum login, atau ke /dashboard jika tidak ada intended URL.
                return redirect()->intended('/dashboard');
            } else {
                // Untuk kasir (sebelumnya disebut admin di BAB IV), redirect ke input-transaksi
                return redirect()->intended('/input-transaksi');
            }
        }

        // Jika login gagal
        return back()->withErrors(['username' => 'Login gagal! Username atau password salah.']);
    }

    // Method redirectTo() ini tidak akan dipanggil jika Anda menggunakan redirect() langsung di method login().
    // Anda bisa biarkan atau hapus jika tidak digunakan oleh trait AuthenticatesUsers.
    /*
    protected function redirectTo()
    {
        $role = auth()->user()->role;
        if ($role === 'pemilik') {
            return '/dashboard';
        } else {
            return '/input-transaksi';
        }
    }
    */

    // Jika Anda TIDAK menggunakan fitur registrasi di aplikasi, Anda bisa menghapus method ini
    // dan rute yang terkait (showRegister dan register).
    public function showRegister() {
        return view('register');
    }

    // Jika Anda TIDAK menggunakan fitur registrasi di aplikasi, Anda bisa menghapus method ini
    // dan rute yang terkait.
    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users', // Tambahkan validasi username
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username, // Pastikan username disimpan
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kasir', // Default role untuk registrasi baru jika diperlukan
        ]);

        // Auto login after register
        Auth::login($user);
        return redirect('/dashboard')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . '.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}