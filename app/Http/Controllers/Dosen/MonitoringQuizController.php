<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\QuizScore;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MonitoringQuizController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $scores = QuizScore::with(['user', 'quiz'])
            ->when($search, function ($query, $search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
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
}
