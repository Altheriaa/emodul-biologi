<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi';

    protected $fillable = [ 
        'judul',
        'deskripsi',
        'tanggal_rilis',
        'link_flipping',
        'cover_path',
        'jumlah_halaman'
    ];
}
