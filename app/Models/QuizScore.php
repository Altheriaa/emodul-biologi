<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizScore extends Model
{
    protected $table = 'quiz_scores';

    protected $fillable = [
        'quiz_id',
        'user_id',
        'score',
        'total_questions',
        'correct_answers',
        'answers',
        'is_passed',
        'submitted_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'is_passed' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
