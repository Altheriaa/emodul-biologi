<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = Auth::user();

        if (! $admin->isAdmin()) {
            return abort(404);
        }

        return Inertia::render('RoleAdmin/Settings', [
            'admin' => $admin,
        ]);
    }

    public function update(Request $request)
    {
        $admin = Auth::user();

        if (! $admin->isAdmin()) {
            return abort(404);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$admin->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $admin->update([
                'password' => $request->password,
            ]);
        }

        return redirect('/admin/settings')->with('success', 'Data berhasil diperbarui!');
    }
}
