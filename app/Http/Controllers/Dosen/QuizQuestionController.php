<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizQuestionController extends Controller
{
    public function store(Request $request, string $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);

        if ($quiz->status === 'published') {
            return back()->withErrors(['error' => 'Pertanyaan tidak dapat ditambahkan karena kuis telah dipublikasikan.']);
        }

        $request->validate([
            'question_text' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'options' => 'required|array|min:2|max:5',
            'options.*.option_text' => 'required|string|max:500',
            'options.*.is_correct' => 'required|boolean',
        ], [
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $correctCount = collect($request->options)->filter(fn ($o) => $o['is_correct'])->count();

        if ($correctCount !== 1) {
            return back()->withErrors(['options' => 'Tepat satu jawaban harus ditandai benar.']);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('quiz-questions', 'public');
        }

        $lastOrder = QuizQuestion::where('quiz_id', $quizId)->max('order') ?? 0;

        $question = QuizQuestion::create([
            'quiz_id' => $quizId,
            'question_text' => $request->question_text,
            'image' => $imagePath,
            'question_type' => 'multiple_choice',
            'order' => $lastOrder + 1,
        ]);

        $labels = ['A', 'B', 'C', 'D', 'E'];

        foreach ($request->options as $index => $option) {
            QuizQuestionOption::create([
                'quiz_question_id' => $question->id,
                'option_label' => $labels[$index] ?? chr(65 + $index),
                'option_text' => $option['option_text'],
                'is_correct' => (bool) $option['is_correct'],
            ]);
        }

        return redirect("/dosen/evaluasi/bank-soal/{$quizId}/edit")
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    public function update(Request $request, string $quizId, string $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);

        if ($quiz->status === 'published') {
            return back()->withErrors(['error' => 'Pertanyaan tidak dapat diubah karena kuis telah dipublikasikan.']);
        }

        $question = QuizQuestion::where('quiz_id', $quizId)->findOrFail($questionId);

        $request->validate([
            'question_text' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean',
            'options' => 'required|array|min:2|max:5',
            'options.*.option_text' => 'required|string|max:500',
            'options.*.is_correct' => 'required|boolean',
        ], [
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        $correctCount = collect($request->options)->filter(fn ($o) => $o['is_correct'])->count();

        if ($correctCount !== 1) {
            return back()->withErrors(['options' => 'Tepat satu jawaban harus ditandai benar.']);
        }

        $imagePath = $question->image;
        if ($request->remove_image && $imagePath) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        } elseif ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('quiz-questions', 'public');
        }

        $question->update([
            'question_text' => $request->question_text,
            'image' => $imagePath,
        ]);

        $question->options()->delete();

        $labels = ['A', 'B', 'C', 'D', 'E'];

        foreach ($request->options as $index => $option) {
            QuizQuestionOption::create([
                'quiz_question_id' => $question->id,
                'option_label' => $labels[$index] ?? chr(65 + $index),
                'option_text' => $option['option_text'],
                'is_correct' => (bool) $option['is_correct'],
            ]);
        }

        return redirect("/dosen/evaluasi/bank-soal/{$quizId}/edit")
            ->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    public function destroy(string $quizId, string $questionId)
    {
        $quiz = Quiz::findOrFail($quizId);

        if ($quiz->status === 'published') {
            return back()->withErrors(['error' => 'Pertanyaan tidak dapat dihapus karena kuis telah dipublikasikan.']);
        }

        $question = QuizQuestion::where('quiz_id', $quizId)->findOrFail($questionId);

        if ($question->image) {
            Storage::disk('public')->delete($question->image);
        }

        $question->delete();

        return redirect("/dosen/evaluasi/bank-soal/{$quizId}/edit")
            ->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
