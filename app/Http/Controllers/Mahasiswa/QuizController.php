<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class QuizController extends Controller
{
    /**
     * Tampilkan daftar quiz yang tersedia (published).
     */
    public function index()
    {
        $userId = Auth::id();

        $quizzes = Quiz::withCount('questions')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($quiz) use ($userId) {
                $score = QuizScore::where('quiz_id', $quiz->id)
                    ->where('user_id', $userId)
                    ->first();

                $activeAttempt = QuizAttempt::where('quiz_id', $quiz->id)
                    ->where('user_id', $userId)
                    ->where('is_submitted', false)
                    ->where('end_at', '>', now())
                    ->exists();

                return [
                    'id' => $quiz->id,
                    'title' => $quiz->title,
                    'description' => $quiz->description,
                    'duration_minutes' => $quiz->duration_minutes,
                    'questions_count' => $quiz->questions_count,
                    'score' => $score ? $score->score : null,
                    'is_passed' => $score ? $score->is_passed : null,
                    'submitted_at' => $score ? $score->submitted_at : null,
                    'is_ongoing' => $activeAttempt,
                ];
            });

        return Inertia::render('RoleMahasiswa/Quiz', [
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * Tampilkan halaman pengerjaan quiz.
     */
    public function start(string $quizId)
    {
        $quiz = Quiz::with(['questions.options'])
            ->where('status', 'published')
            ->findOrFail($quizId);

        $userId = Auth::id();

        // Cek apakah sudah pernah submit
        $existingScore = QuizScore::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->first();

        if ($existingScore) {
            return redirect("/mahasiswa/evaluasi/quiz/{$quizId}/result");
        }

        // Timer persistence logic
        $attempt = QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->first();

        if (! $attempt) {
            $startedAt = now();
            $endAt = $startedAt->copy()->addMinutes($quiz->duration_minutes);
            $attempt = QuizAttempt::create([
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'started_at' => $startedAt,
                'end_at' => $endAt,
                'is_submitted' => false,
            ]);
        }

        $now = now();
        $remainingSeconds = $now->greaterThanOrEqualTo($attempt->end_at)
            ? 0
            : $now->diffInSeconds($attempt->end_at);

        // Acak urutan soal dan sembunyikan is_correct dari client
        $questions = $quiz->questions->map(function ($q) {
            return [
                'id' => $q->id,
                'question_text' => $q->question_text,
                'order' => $q->order,
                'options' => $q->options->map(fn ($o) => [
                    'id' => $o->id,
                    'option_label' => $o->option_label,
                    'option_text' => $o->option_text,
                    // JANGAN kirim is_correct ke client
                ])->values(),
            ];
        })->values();

        return Inertia::render('RoleMahasiswa/QuizStart', [
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'description' => $quiz->description,
                'duration_minutes' => $quiz->duration_minutes,
                'remaining_seconds' => $remainingSeconds,
            ],
            'questions' => $questions,
        ]);
    }

    /**
     * Submit jawaban dan hitung skor.
     *
     * @param  array<int, int>  $answers  { question_id => option_id }
     */
    public function submit(Request $request, string $quizId)
    {
        $quiz = Quiz::with(['questions.options'])
            ->where('status', 'published')
            ->findOrFail($quizId);

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|integer',
        ]);

        $userId = Auth::id();

        // Mencegah submit ulang
        $existingScore = QuizScore::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->first();

        if ($existingScore) {
            return redirect("/mahasiswa/evaluasi/quiz/{$quizId}/result");
        }

        $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'nullable|integer',
        ]);

        $userAnswers = $request->answers; // { question_id => option_id }

        $correctCount = 0;
        $totalQuestions = $quiz->questions->count();

        foreach ($quiz->questions as $question) {
            $selectedOptionId = $userAnswers[$question->id] ?? null;

            if ($selectedOptionId) {
                $correctOption = $question->options->firstWhere('is_correct', true);
                if ($correctOption && (int) $selectedOptionId === $correctOption->id) {
                    $correctCount++;
                }
            }
        }

        $score = $totalQuestions > 0 ? round(($correctCount / $totalQuestions) * 100) : 0;
        $isPassed = $score >= 70; // passing grade default 70

        QuizScore::create([
            'quiz_id' => $quizId,
            'user_id' => $userId,
            'score' => $score,
            'total_questions' => $totalQuestions,
            'correct_answers' => $correctCount,
            'answers' => $userAnswers,
            'is_passed' => $isPassed,
            'submitted_at' => now(),
        ]);

        // Update attempt status
        QuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->update(['is_submitted' => true]);

        return redirect("/mahasiswa/evaluasi/quiz/{$quizId}/result");
    }

    /**
     * Tampilkan halaman hasil quiz.
     */
    public function result(string $quizId)
    {
        $quiz = Quiz::findOrFail($quizId);
        $userId = Auth::id();

        $score = QuizScore::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return Inertia::render('RoleMahasiswa/QuizResult', [
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
            ],
            'score' => [
                'score' => $score->score,
                'is_passed' => $score->is_passed,
                'correct_answers' => $score->correct_answers,
                'total_questions' => $score->total_questions,
                'submitted_at' => $score->submitted_at,
            ],
        ]);
    }

    /**
     * Tampilkan halaman riwayat nilai kuis mahasiswa.
     */
    public function history()
    {
        $userId = Auth::id();

        $history = QuizScore::with('quiz')
            ->where('user_id', $userId)
            ->orderBy('submitted_at', 'desc')
            ->get()
            ->map(fn ($score) => [
                'id' => $score->id,
                'quiz_id' => $score->quiz_id,
                'quiz_title' => $score->quiz->title,
                'score' => $score->score,
                'correct_answers' => $score->correct_answers,
                'total_questions' => $score->total_questions,
                'is_passed' => $score->is_passed,
                'submitted_at' => $score->submitted_at->format('d M Y, H:i'),
            ]);

        return Inertia::render('RoleMahasiswa/QuizHistory', [
            'history' => $history,
        ]);
    }
}
