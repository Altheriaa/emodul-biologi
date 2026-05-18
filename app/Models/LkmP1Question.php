<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LkmP1Question extends Model
{
    protected $guarded = [];

    public function submission()
    {
        return $this->belongsTo(LkmSubmission::class);
    }
}
