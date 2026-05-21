<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LkmSubmission extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'pertemuan' => 'integer',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function lkmSetting()
    {
        return $this->belongsTo(LkmSetting::class);
    }

    public function p1Observations()
    {
        return $this->hasMany(LkmP1Observation::class);
    }

    public function p1Questions()
    {
        return $this->hasOne(LkmP1Question::class);
    }

    public function p2Specs()
    {
        return $this->hasOne(LkmP2Spec::class);
    }

    public function p2Items()
    {
        return $this->hasMany(LkmP2Item::class);
    }

    public function p2Steps()
    {
        return $this->hasMany(LkmP2Step::class);
    }

    public function p3Monitorings()
    {
        return $this->hasMany(LkmP3Monitoring::class);
    }

    public function p4Finals()
    {
        return $this->hasOne(LkmP4Final::class);
    }

    public function p4Reflections()
    {
        return $this->hasOne(LkmP4Reflection::class);
    }
}
