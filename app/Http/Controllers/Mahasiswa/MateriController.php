<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MateriController extends Controller
{
    public function index() {

        $materis = Materi::orderBy('tanggal_rilis', 'desc')->get();

        return Inertia::render('RoleMahasiswa/Pembelajaran/Index', [
            'materis' => $materis,
        ]);
    }

    public function show(string $id)
    {
        $materi = Materi::findOrFail($id);

        return Inertia::render('RoleMahasiswa/Pembelajaran/Detail', [
            'materi' => $materi,
        ]);
    }
}
