<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MateriController extends Controller
{
    public function index() {

        $materis = Materi::orderBy('tanggal_rilis', 'desc')->get();

        return Inertia::render('RoleAdmin/Pembelajaran/Index', [
            'materis' => $materis,
        ]);
    }

    public function create() {
        return Inertia::render('RoleAdmin/Pembelajaran/Create');
    }

    public function store(Request $request) {
        
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_rilis' => 'required|date',
            'link_flipping' => 'nullable|string|url',
            'jumlah_halaman' => 'required|integer',
            'cover_path' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_cover_path' => 'nullable|boolean',
        ]);
        
        if ($request->hasFile('cover_path')) {
            $validated['cover_path'] = $request->file('cover_path')->store('cover_path', 'public');
        } elseif ($request->boolean('remove_cover_path')) {
            $validated['cover_path'] = null;
        } else {
            unset($validated['cover_path']);
        }

        Materi::create($validated);

        return redirect('/admin/pembelajaran/materi')->with('success', 'Data Materi berhasil ditambahkan!');
    }

    public function destroy(string $id)
    {
        $materi = Materi::findOrFail($id);

        $materi->delete();

        return redirect('/admin/pembelajaran/materi')->with('success', 'Data Materi berhasil dihapus!');
    }

    public function show(string $id)
    {
        $materi = Materi::findOrFail($id);

        return Inertia::render('RoleAdmin/Pembelajaran/Detail', [
            'materi' => $materi,
        ]);
    }

    public function edit(string $id)
    {
        $materi = Materi::findOrFail($id);

        return Inertia::render('RoleAdmin/Pembelajaran/Edit', [
            'materi' => $materi,
        ]);
    }

    public function update(Request $request, string $id) {

        $materi = Materi::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_rilis' => 'nullable|date',
            'link_flipping' => 'nullable|string|url',
            'jumlah_halaman' => 'nullable|integer',
            'cover_path' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_cover_path' => 'nullable|boolean',
        ]);

        if ($request->hasFile('cover_path')) {
            $validated['cover_path'] = $request->file('cover_path')->store('cover_path', 'public');
        } elseif ($request->boolean('remove_cover_path')) {
            $validated['cover_path'] = null;
        } else {
            unset($validated['cover_path']);
        }

        $materi->update($validated);

        return redirect('/admin/pembelajaran/materi')->with('success', 'Data Materi berhasil diubah!');
    }
}
