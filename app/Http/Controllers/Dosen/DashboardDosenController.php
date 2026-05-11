<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardDosenController extends Controller
{
    public function index()
    {
        return Inertia::render('RoleDosen/DashboardDosen');
    }
}
