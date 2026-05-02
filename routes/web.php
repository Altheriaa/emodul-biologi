<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

Route::middleware(['auth'])->group(function () {

    // MAHASISWA
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.mahasiswa');

        route::prefix('informasi-modul')->group(function () {
            Route::get('/identitas-modul', function () {
                return Inertia::render('RoleMahasiswa/IdentitasModul');
            });
            Route::get('/cpl-cpmk', function () {
                return Inertia::render('RoleMahasiswa/CplCpmk');
            });
        });

        Route::prefix('pembelajaran')->group(function () {
             Route::get('/materi', function () {
                return Inertia::render('RoleMahasiswa/Materi');
            });
            Route::get('/materi/flipping-book', function () {
                return Inertia::render('RoleMahasiswa/Flipping');
            });
        });

       Route::get('/settings', [ProfileController::class, 'index']);
    });

    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
        Route::resource('/dosen', DosenController::class);
    });

});





