<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $count_dosen = User::where('role', 'dosen')->count();
        $count_mahasiswa = User::where('role', 'mahasiswa')->count();
        $count_materi = Materi::count();

        return Inertia::render('RoleAdmin/DashboardAdmin', [
            'count_dosen' => $count_dosen,
            'count_mahasiswa' => $count_mahasiswa,
            'count_materi' => $count_materi,
        ]);
    }
}
