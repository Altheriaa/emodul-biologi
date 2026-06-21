<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\EssayMateri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class MateriController extends Controller
{
    public function index()
    {

        $materis = Materi::orderBy('tanggal_rilis', 'desc')->get();

        return Inertia::render('RoleMahasiswa/Pembelajaran/Materi/Index', [
            'materis' => $materis,
        ]);
    }

    public function show(string $id)
    {
        $materi = Materi::with(['essayQuestions.answers' => function ($query) {
            $query->where('mahasiswa_id', Auth::user()->mahasiswa->id);
        }])->findOrFail($id);

        return Inertia::render('RoleMahasiswa/Pembelajaran/Materi/Detail', [
            'materi' => $materi,
        ]);
    }

    public function submitEssay(Request $request, string $id)
    {
        $materi = Materi::findOrFail($id);
        
        $validated = $request->validate([
            'jawaban' => 'required|array',
            'jawaban.*.materi_essay_question_id' => 'required|exists:materi_essay_questions,id',
            'jawaban.*.jawaban' => 'required|string',
        ]);

        foreach ($validated['jawaban'] as $jawab) {
            EssayMateri::updateOrCreate(
                [
                    'mahasiswa_id' => Auth::user()->mahasiswa->id,
                    'materi_essay_question_id' => $jawab['materi_essay_question_id']
                ],
                [
                    'jawaban' => $jawab['jawaban']
                ]
            );
        }

        return redirect()->back()->with('success', 'Jawaban essay berhasil disimpan!');
    }
}
