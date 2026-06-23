<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {

        $dosen = Auth::user();

        $user = User::with('dosen')->where('id', $dosen->id)->first();

        if (! $user) {
            return abort(404);
        }

        return Inertia::render('RoleDosen/Settings', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {

        $dosen = Auth::user();

        $user = User::with('dosen')->where('id', $dosen->id)->first();

        if (! $user) {
            return abort(404);
        }

        $request->validate([
            'name' => 'string|required',
            'email' => 'email|required|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8|confirmed',
            'nuptk' => 'integer|required|unique:dosen,nuptk,'.$user->dosen->id,
            'jabatan' => 'string|required|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => $request->password,
            ]);
        }

        $user->dosen->update([
            'nuptk' => $request->nuptk,
            'jabatan' => $request->jabatan,
        ]);

        return redirect('/dosen/settings')->with('success', 'Data Dosen berhasil diperbarui!');
    }
}
