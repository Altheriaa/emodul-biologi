<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Quiz;
use App\Models\QuizScore;
use App\Models\User;
use Inertia\Inertia;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $count_dosen = User::where('role', 'dosen')->count();
        $count_mahasiswa = User::where('role', 'mahasiswa')->count();
        $count_materi = Materi::count();
        $count_quiz = Quiz::count();

        // Recent quiz submissions
        $recent_submissions = QuizScore::with(['user', 'quiz'])
            ->orderBy('submitted_at', 'desc')
            ->limit(5)
            ->get()
            ->map(fn ($score) => [
                'id' => $score->id,
                'user_name' => $score->user->name,
                'quiz_title' => $score->quiz->title,
                'score' => $score->score,
                'submitted_at' => $score->submitted_at->diffForHumans(),
            ]);

        return Inertia::render('RoleAdmin/DashboardAdmin', [
            'stats' => [
                'count_dosen' => $count_dosen,
                'count_mahasiswa' => $count_mahasiswa,
                'count_materi' => $count_materi,
                'count_quiz' => $count_quiz,
            ],
            'recent_submissions' => $recent_submissions,
        ]);
    }
}
