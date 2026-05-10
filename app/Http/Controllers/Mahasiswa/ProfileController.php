<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index() {

        $mahasiswa = Auth::user();

        $user = User::with('mahasiswa')->where('id', $mahasiswa->id)->first();

        if (!$user) {
            return abort(404);
        }

        return Inertia::render('RoleMahasiswa/Settings', [
            'user' => $user
        ]);
    }

    public function update(Request $request) {

        $mahasiswa = Auth::user();

        $user = User::with('mahasiswa')->where('id', $mahasiswa->id)->first();

        if (!$user) {
            return abort(404);
        }

        $request->validate([
            'name' => 'string|required',
            'email' => 'email|required|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8|confirmed',
            'nim' => 'integer|required|unique:mahasiswa,nim,'.$user->mahasiswa->id,
            'angkatan' => 'integer|required',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
        }

        $user->mahasiswa->update([
            'nim' => $request->nim,
            'angkatan' => $request->angkatan,
        ]);

        return redirect('/mahasiswa/settings')->with('success', 'Data Mahasiswa berhasil diperbarui!');
    }
}
