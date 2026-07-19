<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MahasiswaEligible;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MahasiswaEligibleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $mahasiswas = MahasiswaEligible::query()
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', "%{$search}%")
                      ->orWhere('nim', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10)
            ->withQueryString();

        return Inertia::render('RoleAdmin/KelolaMahasiswaEligible/Index', [
            'mahasiswas' => $mahasiswas,
            'title' => 'Master Mahasiswa Eligible',
            'filters' => ['search' => $search],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('RoleAdmin/KelolaMahasiswaEligible/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'string|required',
            'nim' => 'string|required|unique:mahasiswa_eligibles,nim',
        ]);

        MahasiswaEligible::create([
            'nama' => $request->nama,
            'nim' => $request->nim,
        ]);

        return redirect('/admin/mahasiswa-eligible')->with('success', 'Data Mahasiswa Eligible berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $mahasiswa = MahasiswaEligible::findOrFail($id);

        return Inertia::render('RoleAdmin/KelolaMahasiswaEligible/Edit', [
            'mahasiswa' => $mahasiswa,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $mahasiswa = MahasiswaEligible::findOrFail($id);

        $request->validate([
            'nama' => 'string|required',
            'nim' => 'string|required|unique:mahasiswa_eligibles,nim,'.$id,
        ]);

        $mahasiswa->update([
            'nama' => $request->nama,
            'nim' => $request->nim,
        ]);

        return redirect('/admin/mahasiswa-eligible')->with('success', 'Data Mahasiswa Eligible berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mahasiswa = MahasiswaEligible::findOrFail($id);
        $mahasiswa->delete();

        return redirect('/admin/mahasiswa-eligible')->with('success', 'Data Mahasiswa Eligible berhasil dihapus!');
    }
}
