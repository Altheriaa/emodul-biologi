<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LkmSetting extends Model
{
    protected $fillable = [
        'pertemuan',
        'title',
        'deskripsi',
        'open_at',
        'deadline_at',
        'is_active',
        'allow_late_submit',
        'created_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'allow_late_submit' => 'boolean',
        ];
    }
}
