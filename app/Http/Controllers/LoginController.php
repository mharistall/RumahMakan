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
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'pemilik') {
                return redirect('/dashboard');
            } elseif ($user->role === 'kasir') {
                return redirect('/input-transaksi');
            }
            return redirect('/login');
        }
        return back()->withErrors(['email' => 'Login gagal!']);
    }

    protected function redirectTo()
    {
        $role = auth()->user()->role;
        if ($role === 'pemilik') {
            return '/dashboard';
        } elseif ($role === 'kasir') {
            return '/input-transaksi';
        }
        // Default fallback
        return '/login';
    }

    public function showRegister() {
        return view('register');
    }

    public function register(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Auto login after register
        Auth::login($user);
        return redirect('/dashbord')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . '.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}