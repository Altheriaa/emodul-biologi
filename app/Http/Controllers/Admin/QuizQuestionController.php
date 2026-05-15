<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use Illuminate\Http\Request;

class QuizQuestionController extends Controller
{
    /**
     * Store a newly created question with its options.
     */
    public function store(Request $request, string $quizId)
    {
        Quiz::findOrFail($quizId);

        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2|max:5',
            'options.*.option_text' => 'required|string|max:500',
            'options.*.is_correct' => 'required|boolean',
        ], [
            'options.*.option_text.required' => 'Teks pilihan jawaban wajib diisi.',
        ]);

        $correctCount = collect($request->options)->filter(fn ($o) => $o['is_correct'])->count();

        if ($correctCount !== 1) {
            return back()->withErrors(['options' => 'Tepat satu jawaban harus ditandai benar.']);
        }

        $lastOrder = QuizQuestion::where('quiz_id', $quizId)->max('order') ?? 0;

        $question = QuizQuestion::create([
            'quiz_id' => $quizId,
            'question_text' => $request->question_text,
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

        return redirect("/admin/evaluasi/bank-soal/{$quizId}/edit")
            ->with('success', 'Pertanyaan berhasil ditambahkan!');
    }

    /**
     * Update the specified question and its options.
     */
    public function update(Request $request, string $quizId, string $questionId)
    {
        $question = QuizQuestion::where('quiz_id', $quizId)->findOrFail($questionId);

        $request->validate([
            'question_text' => 'required|string',
            'options' => 'required|array|min:2|max:5',
            'options.*.option_text' => 'required|string|max:500',
            'options.*.is_correct' => 'required|boolean',
        ]);

        $correctCount = collect($request->options)->filter(fn ($o) => $o['is_correct'])->count();

        if ($correctCount !== 1) {
            return back()->withErrors(['options' => 'Tepat satu jawaban harus ditandai benar.']);
        }

        $question->update([
            'question_text' => $request->question_text,
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

        return redirect("/admin/evaluasi/bank-soal/{$quizId}/edit")
            ->with('success', 'Pertanyaan berhasil diperbarui!');
    }

    /**
     * Remove the specified question.
     */
    public function destroy(string $quizId, string $questionId)
    {
        $question = QuizQuestion::where('quiz_id', $quizId)->findOrFail($questionId);
        $question->delete();

        return redirect("/admin/evaluasi/bank-soal/{$quizId}/edit")
            ->with('success', 'Pertanyaan berhasil dihapus!');
    }
}
