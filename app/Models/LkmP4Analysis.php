<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LkmP4Analysis extends Model
{
    protected $table = 'lkm_p4_analyses';

    protected $guarded = [];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(LkmSubmission::class, 'lkm_submission_id');
    }
}
