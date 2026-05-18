<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LkmP1Observation extends Model
{
    protected $guarded = [];

    public function submission()
    {
        return $this->belongsTo(LkmSubmission::class);
    }
}
