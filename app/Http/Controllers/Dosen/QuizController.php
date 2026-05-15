<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class QuizController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $quizzes = Quiz::query()
            ->withCount('questions as count_soal')
            ->when($search, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->simplePaginate(10)
            ->withQueryString();

        return Inertia::render('RoleDosen/Evaluasi/BankSoal/Index', [
            'quizzes' => $quizzes,
        ]);
    }

    public function create()
    {
        return Inertia::render('RoleDosen/Evaluasi/BankSoal/Create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'title' => 'required|string|max:255|unique:quizzes,title',
            'description' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:5|max:180',
            'status' => 'required|in:draft,published,archived',
        ]);

        Quiz::create([
            'created_by' => $user->id,
            'title' => $request->title,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'status' => $request->status,
        ]);

        return redirect('/dosen/evaluasi/bank-soal')->with('success', 'Bank Soal berhasil ditambahkan');
    }

    public function edit(string $id)
    {
        $quiz = Quiz::with(['questions.options'])->findOrFail($id);

        return Inertia::render('RoleDosen/Evaluasi/BankSoal/Edit', [
            'quiz' => $quiz,
            'questions' => $quiz->questions,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:quizzes,title,'.$id,
            'description' => 'required|string|max:255',
            'duration_minutes' => 'required|integer|min:5|max:180',
            'status' => 'required|in:draft,published,archived',
        ]);

        $quiz = Quiz::findOrFail($id);

        $quiz->update([
            'title' => $request->title,
            'description' => $request->description,
            'duration_minutes' => $request->duration_minutes,
            'status' => $request->status,
        ]);

        return redirect('/dosen/evaluasi/bank-soal')->with('success', 'Bank Soal berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $quiz = Quiz::findOrFail($id);
        $quiz->delete();

        return redirect('/dosen/evaluasi/bank-soal')->with('success', 'Data Soal berhasil dihapus!');
    }
}
