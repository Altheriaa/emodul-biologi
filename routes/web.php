<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::middleware('guest')->group(function() {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

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

        Route::prefix('evaluasi')->group(function () {
            Route::get('/quiz', function () {
                return Inertia::render('RoleMahasiswa/Quiz');
            });
            Route::get('/quiz/start', function () {
                return Inertia::render('RoleMahasiswa/QuizStart');
            });
        });

       Route::get('/settings', [ProfileController::class, 'index']);
       Route::put('/settings', [ProfileController::class, 'update']);
    });

    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
        
        route::prefix('informasi-modul')->group(function () {
            Route::get('/identitas-modul', function () {
                return Inertia::render('RoleAdmin/InformasiModul/IdentitasModul');
            });
            Route::get('/cpl-cpmk', function () {
                return Inertia::render('RoleAdmin/InformasiModul/CplCpmk');
            });
        });

        Route::prefix('pembelajaran')->group(function () {
            Route::get('/materi', [MateriController::class, 'index']);
            Route::get('/materi/create', [MateriController::class, 'create']);
            Route::post('/materi', [MateriController::class, 'store']);
            Route::delete('/materi/{materi}', [MateriController::class, 'destroy']);
            Route::get('/materi/{materi}', [MateriController::class, 'show']);
            Route::get('/materi/{materi}/edit', [MateriController::class, 'edit']);
            Route::put('/materi/{materi}', [MateriController::class, 'update']);
        });

        Route::resource('/mahasiswa', MahasiswaController::class);
        Route::resource('/dosen', DosenController::class);
        Route::get('/settings', [AdminProfileController::class, 'index']);
        Route::put('/settings', [AdminProfileController::class, 'update']);
    });

});





