<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\QuizScore;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardDosenController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $my_quizzes_ids = Quiz::where('created_by', $userId)->pluck('id');

        $count_my_quizzes = $my_quizzes_ids->count();
        $count_total_materi = Materi::count();
        $count_mahasiswa = User::where('role', 'mahasiswa')->count();

        // Students who took this dosen's quizzes
        $count_students_taken = QuizScore::whereIn('quiz_id', $my_quizzes_ids)
            ->distinct('user_id')
            ->count('user_id');

        // Recent results for this dosen's quizzes
        $recent_results = QuizScore::with(['user', 'quiz'])
            ->whereIn('quiz_id', $my_quizzes_ids)
            ->orderBy('submitted_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn ($score) => [
                'id' => $score->id,
                'user_name' => $score->user->name,
                'quiz_title' => $score->quiz->title,
                'score' => $score->score,
                'is_passed' => $score->is_passed,
                'submitted_at' => $score->submitted_at->diffForHumans(),
            ]);

        return Inertia::render('RoleDosen/DashboardDosen', [
            'stats' => [
                'my_quizzes' => $count_my_quizzes,
                'total_materi' => $count_total_materi,
                'total_mahasiswa' => $count_mahasiswa,
                'students_taken' => $count_students_taken,
            ],
            'recent_results' => $recent_results,
        ]);
    }
}
