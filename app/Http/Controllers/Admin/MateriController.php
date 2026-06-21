<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MateriController extends Controller
{
    public function index()
    {

        $materis = Materi::orderBy('tanggal_rilis', 'desc')->get();

        return Inertia::render('RoleAdmin/Pembelajaran/Materi/Index', [
            'materis' => $materis,
        ]);
    }

    public function create()
    {
        return Inertia::render('RoleAdmin/Pembelajaran/Materi/Create');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'tanggal_rilis' => 'required|date',
            'link_flipping' => 'nullable|string|url',
            'jumlah_halaman' => 'required|integer',
            'cover_path' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_cover_path' => 'nullable|boolean',
            'pertanyaan_essay' => 'nullable|array',
            'pertanyaan_essay.*.id' => 'nullable',
            'pertanyaan_essay.*.pertanyaan' => 'required|string',
        ]);

        if ($request->hasFile('cover_path')) {
            $validated['cover_path'] = $request->file('cover_path')->store('cover_path', 'public');
        } elseif ($request->boolean('remove_cover_path')) {
            $validated['cover_path'] = null;
        } else {
            unset($validated['cover_path']);
        }

        $materi = Materi::create($validated);

        if (!empty($validated['pertanyaan_essay'])) {
            foreach ($validated['pertanyaan_essay'] as $q) {
                $materi->essayQuestions()->create(['pertanyaan' => $q['pertanyaan']]);
            }
        }

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

        return Inertia::render('RoleAdmin/Pembelajaran/Materi/Detail', [
            'materi' => $materi,
        ]);
    }

    public function edit(string $id)
    {
        $materi = Materi::with('essayQuestions')->findOrFail($id);

        return Inertia::render('RoleAdmin/Pembelajaran/Materi/Edit', [
            'materi' => $materi,
        ]);
    }

    public function update(Request $request, string $id)
    {

        $materi = Materi::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_rilis' => 'nullable|date',
            'link_flipping' => 'nullable|string|url',
            'jumlah_halaman' => 'nullable|integer',
            'cover_path' => 'nullable|file|mimes:jpeg,png,jpg,webp|max:2048',
            'remove_cover_path' => 'nullable|boolean',
            'pertanyaan_essay' => 'nullable|array',
            'pertanyaan_essay.*.id' => 'nullable',
            'pertanyaan_essay.*.pertanyaan' => 'required|string',
        ]);

        if ($request->hasFile('cover_path')) {
            $validated['cover_path'] = $request->file('cover_path')->store('cover_path', 'public');
        } elseif ($request->boolean('remove_cover_path')) {
            $validated['cover_path'] = null;
        } else {
            unset($validated['cover_path']);
        }

        $materi->update($validated);

        if (isset($validated['pertanyaan_essay'])) {
            $existingIds = collect($validated['pertanyaan_essay'])->pluck('id')->filter()->toArray();
            $materi->essayQuestions()->whereNotIn('id', $existingIds)->delete();

            foreach ($validated['pertanyaan_essay'] as $q) {
                if (isset($q['id']) && $q['id']) {
                    $materi->essayQuestions()->where('id', $q['id'])->update(['pertanyaan' => $q['pertanyaan']]);
                } else {
                    $materi->essayQuestions()->create(['pertanyaan' => $q['pertanyaan']]);
                }
            }
        } else {
            $materi->essayQuestions()->delete();
        }

        return redirect('/admin/pembelajaran/materi')->with('success', 'Data Materi berhasil diubah!');
    }

    public function essayMonitoring(string $id)
    {
        $materi = Materi::with(['essayQuestions.answers.mahasiswa.user'])->findOrFail($id);

        return Inertia::render('RoleAdmin/Pembelajaran/Materi/MonitoringEssay', [
            'materi' => $materi,
        ]);
    }
}
