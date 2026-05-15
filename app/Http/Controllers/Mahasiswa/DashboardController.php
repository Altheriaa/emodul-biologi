<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\QuizScore;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('mahasiswa');

        $scores = QuizScore::where('user_id', $user->id)->get();
        $count_quiz = $scores->count();
        $avg_score = $scores->avg('score') ?? 0;

        $recent_materi = Materi::orderBy('tanggal_rilis', 'desc')
            ->limit(3)
            ->get();

        return Inertia::render('RoleMahasiswa/Dashboard', [
            'user' => [
                'name' => $user->name,
                'nim' => $user->mahasiswa->nim ?? '-',
                'angkatan' => $user->mahasiswa->angkatan ?? '-',
                'email' => $user->email,
            ],
            'stats' => [
                'quiz_taken' => $count_quiz,
                'avg_score' => round($avg_score, 1),
            ],
            'recent_materi' => $recent_materi,
        ]);
    }
}
