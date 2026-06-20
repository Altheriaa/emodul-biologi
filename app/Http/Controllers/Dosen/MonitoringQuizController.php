<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\QuizScore;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MonitoringQuizController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->input('search');
        $scores = QuizScore::with(['user.mahasiswa', 'quiz'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhereHas('mahasiswa', function ($q2) use ($search) {
                            $q2->where('nim', 'like', "%{$search}%");
                        });
                })->orWhereHas('quiz', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            })
            ->orderBy('submitted_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('RoleDosen/Evaluasi/Monitoring/Index', [
            'scores' => $scores,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(string $id): Response
    {
        $score = QuizScore::with(['user.mahasiswa', 'quiz.questions.options'])->findOrFail($id);

        return Inertia::render('RoleDosen/Evaluasi/Monitoring/Show', [
            'score' => $score,
        ]);
    }
}
