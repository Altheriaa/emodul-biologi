<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EssayMateri extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'materi_essay_question_id',
        'jawaban'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function question()
    {
        return $this->belongsTo(MateriEssayQuestion::class, 'materi_essay_question_id');
    }
}