<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriEssayQuestion extends Model
{
    protected $fillable = ['materi_id', 'pertanyaan'];

    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    public function answers()
    {
        return $this->hasMany(EssayMateri::class, 'materi_essay_question_id');
    }
}