<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';
    
    protected $fillable = [
        'created_by',
        'title',
        'description',
        'duration_minutes',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
