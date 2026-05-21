<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Inertia\Inertia;

class MateriController extends Controller
{
    public function index()
    {

        $materis = Materi::orderBy('tanggal_rilis', 'desc')->get();

        return Inertia::render('RoleMahasiswa/Pembelajaran/Materi/Index', [
            'materis' => $materis,
        ]);
    }

    public function show(string $id)
    {
        $materi = Materi::findOrFail($id);

        return Inertia::render('RoleMahasiswa/Pembelajaran/Materi/Detail', [
            'materi' => $materi,
        ]);
    }
}
