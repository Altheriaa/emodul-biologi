<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dosens = User::with('dosen')->where('role', 'dosen')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhereHas('dosen', function ($dosen) use ($search) {
                            $dosen->where('nuptk', 'like', "%{$search}%");
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10)
            ->withQueryString();

        return Inertia::render('RoleAdmin/KelolaDosen/Index', [
            'dosens' => $dosens,
            'title' => 'Dosen',
            'filters' => ['search' => $search],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('RoleAdmin/KelolaDosen/Create');
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
            'nuptk' => 'integer|required|unique:dosen,nuptk',
            'jabatan' => 'string|required|max:50',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'dosen',
            ]);

            Dosen::create([
                'user_id' => $user->id,
                'nuptk' => $request->nuptk,
                'jabatan' => $request->jabatan,
            ]);
        });

        return redirect('/admin/dosen')->with('success', 'Data Dosen berhasil ditambahkan!');

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
        $dosen = Dosen::with('user')->findOrFail($id);

        return Inertia::render('RoleAdmin/KelolaDosen/Edit', [
            'dosen' => $dosen,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dosen = Dosen::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'string|required',
            'email' => 'email|required|unique:users,email,'.$dosen->user_id,
            'password' => 'nullable|min:8|confirmed',
            'nuptk' => 'integer|required|unique:dosen,nuptk,'.$id,
            'jabatan' => 'string|required|max:50',
        ]);

        $dosen->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $dosen->user->update([
                'password' => $request->password,
            ]);
        }

        $dosen->update([
            'nuptk' => $request->nuptk,
            'jabatan' => $request->jabatan,
        ]);

        return redirect('/admin/dosen')->with('success', 'Data Dosen berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dosen = Dosen::with('user')->findOrFail($id);

        $dosen->user->delete();
        $dosen->delete();

        return redirect('/admin/dosen')->with('success', 'Data Dosen berhasil dihapus!');
    }
}
