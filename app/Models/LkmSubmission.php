<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LkmSubmission extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'pertemuan' => 'integer',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function lkmSetting(): BelongsTo
    {
        return $this->belongsTo(LkmSetting::class);
    }

    // ============================================================
    // LKM Pertemuan 1 Relationships
    // ============================================================

    public function p1Questions(): HasOne
    {
        return $this->hasOne(LkmP1Question::class);
    }

    public function p1Specs(): HasMany
    {
        return $this->hasMany(LkmP1Spec::class);
    }

    public function p1Items(): HasMany
    {
        return $this->hasMany(LkmP1Item::class);
    }

    public function p1Procedures(): HasMany
    {
        return $this->hasMany(LkmP1Procedure::class);
    }

    public function p1Schedules(): HasMany
    {
        return $this->hasMany(LkmP1Schedule::class);
    }

    // ============================================================
    // LKM Pertemuan 2 Relationships
    // ============================================================

    public function p2Items(): HasMany
    {
        return $this->hasMany(LkmP2Item::class);
    }

    public function p2Specs(): HasMany
    {
        return $this->hasMany(LkmP2Spec::class);
    }

    public function p2Procedures(): HasMany
    {
        return $this->hasMany(LkmP2Procedure::class);
    }

    public function p2Monitorings(): HasMany
    {
        return $this->hasMany(LkmP2Monitoring::class);
    }

    public function p2Questions(): HasOne
    {
        return $this->hasOne(LkmP2Question::class);
    }

    // ============================================================
    // LKM Pertemuan 3 Relationships
    // ============================================================

    public function p3Growths(): HasMany
    {
        return $this->hasMany(LkmP3Growth::class);
    }

    public function p3Scions(): HasMany
    {
        return $this->hasMany(LkmP3Scion::class);
    }

    public function p3Rootstocks(): HasMany
    {
        return $this->hasMany(LkmP3Rootstock::class);
    }

    public function p3Connections(): HasOne
    {
        return $this->hasOne(LkmP3Connection::class);
    }

    public function p3Questions(): HasOne
    {
        return $this->hasOne(LkmP3Question::class);
    }

    // ============================================================
    // LKM Pertemuan 4 Relationships
    // ============================================================

    public function p4Analyses(): HasMany
    {
        return $this->hasMany(LkmP4Analysis::class);
    }

    public function p4DeepQuestions(): HasOne
    {
        return $this->hasOne(LkmP4DeepQuestion::class);
    }

    public function p4SelfAssessments(): HasMany
    {
        return $this->hasMany(LkmP4SelfAssessment::class);
    }

    public function p4Reflections(): HasOne
    {
        return $this->hasOne(LkmP4Reflection::class);
    }
}
