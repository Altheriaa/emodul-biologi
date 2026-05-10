<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardAdminController extends Controller
{
    public function index()
    {
        $count_dosen = User::where('role', 'dosen')->count();
        $count_mahasiswa = User::where('role', 'mahasiswa')->count();

        return Inertia::render('RoleAdmin/DashboardAdmin', [
            'count_dosen' => $count_dosen,
            'count_mahasiswa' => $count_mahasiswa,
        ]);
    }
}
