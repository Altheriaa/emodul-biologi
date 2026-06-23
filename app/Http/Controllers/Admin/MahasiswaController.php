<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $mahasiswas = User::with('mahasiswa')->where('role', 'mahasiswa')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('mahasiswa', function ($mahasiswa) use ($search) {
                            $mahasiswa->where('nim', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10)
            ->withQueryString();

        return Inertia::render('RoleAdmin/KelolaMahasiswa/Index', [
            'mahasiswas' => $mahasiswas,
            'title' => 'Mahasiswa',
            'filters' => ['search' => $search],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('RoleAdmin/KelolaMahasiswa/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'string|required',
            'email' => 'email|required|unique:users,email',
            'password' => 'min:8|required|confirmed',
            'nim' => 'integer|required|unique:mahasiswa,nim',
            'angkatan' => 'integer|required',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'mahasiswa',
            ]);

            Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $request->nim,
                'angkatan' => $request->angkatan,
            ]);
        });

        return redirect('/admin/mahasiswa')->with('success', 'Data Mahasiswa berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        return Inertia::render('RoleAdmin/KelolaMahasiswa/Edit', [
            'mahasiswa' => $mahasiswa,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'string|required',
            'email' => 'email|required|unique:users,email,'.$mahasiswa->user_id,
            'password' => 'nullable|min:8|confirmed',
            'nim' => 'integer|required|unique:mahasiswa,nim,'.$id,
            'angkatan' => 'integer|required',
        ]);

        $mahasiswa->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $mahasiswa->user->update([
                'password' => $request->password,
            ]);
        }

        $mahasiswa->update([
            'nim' => $request->nim,
            'angkatan' => $request->angkatan,
        ]);

        return redirect('/admin/mahasiswa')->with('success', 'Data Mahasiswa berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mahasiswa = Mahasiswa::with('user')->findOrFail($id);

        $mahasiswa->user->delete();
        $mahasiswa->delete();

        return redirect('/admin/mahasiswa')->with('success', 'Data Mahasiswa berhasil dihapus!');
    }
}
