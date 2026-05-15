<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\QuizQuestionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Dosen\DashboardDosenController;
use App\Http\Controllers\Dosen\MateriController as DosenMateriController;
use App\Http\Controllers\Dosen\ProfileController as DosenProfileController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\MateriController as MahasiswaMateriController;
use App\Http\Controllers\Mahasiswa\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // forgot password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'index'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'send'])->name('password.email');

    // reset password
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'index'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware(['auth'])->group(function () {

    // Mahasiswa
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.mahasiswa');

        Route::prefix('informasi-modul')->group(function () {
            Route::get('/identitas-modul', function () {
                return Inertia::render('RoleMahasiswa/InformasiModul/IdentitasModul');
            });
            Route::get('/cpl-cpmk', function () {
                return Inertia::render('RoleMahasiswa/InformasiModul/CplCpmk');
            });
        });

        Route::prefix('pembelajaran')->group(function () {
            Route::get('/materi', [MahasiswaMateriController::class, 'index']);
            Route::get('/materi/{materi}', [MahasiswaMateriController::class, 'show']);
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

    Route::middleware('role:dosen')->prefix('dosen')->group(function () {
        Route::get('/dashboard', [DashboardDosenController::class, 'index'])->name('dashboard.dosen');

        Route::prefix('informasi-modul')->group(function () {
            Route::get('/identitas-modul', function () {
                return Inertia::render('RoleDosen/InformasiModul/IdentitasModul');
            });
            Route::get('/cpl-cpmk', function () {
                return Inertia::render('RoleDosen/InformasiModul/CplCpmk');
            });
        });

        Route::prefix('pembelajaran')->group(function () {
            Route::get('/materi', [DosenMateriController::class, 'index']);
            Route::get('/materi/create', [DosenMateriController::class, 'create']);
            Route::post('/materi', [DosenMateriController::class, 'store']);
            Route::delete('/materi/{materi}', [DosenMateriController::class, 'destroy']);
            Route::get('/materi/{materi}', [DosenMateriController::class, 'show']);
            Route::get('/materi/{materi}/edit', [DosenMateriController::class, 'edit']);
            Route::put('/materi/{materi}', [DosenMateriController::class, 'update']);
        });

        // Route::prefix('evaluasi')->group(function () {
        //     Route::get('/quiz', function () {
        //         return Inertia::render('RoleMahasiswa/Quiz');
        //     });
        //     Route::get('/quiz/start', function () {
        //         return Inertia::render('RoleMahasiswa/QuizStart');
        //     });
        // });

        Route::get('/settings', [DosenProfileController::class, 'index']);
        Route::put('/settings', [DosenProfileController::class, 'update']);
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard.admin');

        Route::prefix('informasi-modul')->group(function () {
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

        Route::prefix('evaluasi')->group(function () {
            Route::get('/bank-soal', [QuizController::class, 'index']);
            Route::get('/bank-soal/create', [QuizController::class, 'create']);
            Route::post('/bank-soal', [QuizController::class, 'store']);
            Route::get('/bank-soal/{quiz}/edit', [QuizController::class, 'edit']);
            Route::put('/bank-soal/{quiz}', [QuizController::class, 'update']);
            Route::delete('/bank-soal/{quiz}', [QuizController::class, 'destroy']);

            // Quiz questions (soal)
            Route::post('/bank-soal/{quiz}/soal', [QuizQuestionController::class, 'store']);
            Route::put('/bank-soal/{quiz}/soal/{question}', [QuizQuestionController::class, 'update']);
            Route::delete('/bank-soal/{quiz}/soal/{question}', [QuizQuestionController::class, 'destroy']);
        });

        Route::resource('/mahasiswa', MahasiswaController::class);
        Route::resource('/dosen', DosenController::class);
        Route::get('/settings', [AdminProfileController::class, 'index']);
        Route::put('/settings', [AdminProfileController::class, 'update']);
    });

});
