<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LkmP2Step extends Model
{
    protected $guarded = [];

    public function submission()
    {
        return $this->belongsTo(LkmSubmission::class);
    }
}
