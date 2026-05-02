<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (! $credentials) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ]);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->route('dashboard.admin')->with('success', 'Login berhasil.');
            }
            if (Auth::user()->isDosen()) {
                return redirect()->route('dashboard.dosen')->with('success', 'Login berhasil.');
            }
            if (Auth::user()->isMahasiswa()) {
                return redirect()->route('dashboard.mahasiswa')->with('success', 'Login berhasil.');
            }
        }

        return back()->withErrors(['email' => 'Email atau Password salah']);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
