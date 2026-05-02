<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Mahasiswa;

class RegisterController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Register');
    }

    public function store(Request $request) {

        $request->validate([
            'name' => 'string|required',
            'email' => 'email|required|unique:users,email',
            'password' => 'min:8|required',
            'nim' => 'integer|required|unique:mahasiswa,nim',
            'angkatan' => 'string|required|max:4',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'mahasiswa',
        ]);

        Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $request->nim,
            'angkatan' => $request->angkatan,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil.');
    }
}
