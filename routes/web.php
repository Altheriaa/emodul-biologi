<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\DosenController;
use App\Http\Controllers\Admin\LKMGraftingController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\MateriController;
use App\Http\Controllers\Admin\MonitoringQuizController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\QuizQuestionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Dosen\DashboardDosenController;
use App\Http\Controllers\Dosen\LKMGraftingController as DosenLKMGraftingController;
use App\Http\Controllers\Dosen\MateriController as DosenMateriController;
use App\Http\Controllers\Dosen\MonitoringQuizController as DosenMonitoringQuizController;
use App\Http\Controllers\Dosen\ProfileController as DosenProfileController;
use App\Http\Controllers\Dosen\QuizController as DosenQuizController;
use App\Http\Controllers\Dosen\QuizQuestionController as DosenQuizQuestionController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use App\Http\Controllers\Mahasiswa\LKMGraftingController as MahasiswaLKMGraftingController;
use App\Http\Controllers\Mahasiswa\MateriController as MahasiswaMateriController;
use App\Http\Controllers\Mahasiswa\ProfileController;
use App\Http\Controllers\Mahasiswa\QuizController as MahasiswaQuizController;
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

            // LKM Grafting
            Route::get('/lkm-grafting', [MahasiswaLKMGraftingController::class, 'index']);
            Route::get('/lkm-grafting/form/{pertemuan}', [MahasiswaLKMGraftingController::class, 'showForm']);
            Route::post('/lkm-grafting/form/{pertemuan}', [MahasiswaLKMGraftingController::class, 'storeData']);
        });

        Route::prefix('evaluasi')->group(function () {
            Route::get('/quiz', [MahasiswaQuizController::class, 'index']);
            Route::get('/quiz/history', [MahasiswaQuizController::class, 'history']);
            Route::get('/quiz/{quiz}/start', [MahasiswaQuizController::class, 'start']);
            Route::post('/quiz/{quiz}/submit', [MahasiswaQuizController::class, 'submit']);
            Route::get('/quiz/{quiz}/result', [MahasiswaQuizController::class, 'result']);
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

            // LKM Grafting Submission for Dosen
            Route::get('/lkm-grafting/submissions', [DosenLKMGraftingController::class, 'indexSubmission']);
            Route::get('/lkm-grafting/submissions/mahasiswa/{mahasiswaId}', [DosenLKMGraftingController::class, 'showMahasiswaSubmissions']);
            Route::get('/lkm-grafting/submissions/{id}', [DosenLKMGraftingController::class, 'showSubmission']);
            Route::post('/lkm-grafting/submissions/{id}/catatan', [DosenLKMGraftingController::class, 'updateCatatan']);
        });

        // Route::prefix('evaluasi')->group(function () {
        //     Route::get('/quiz', function () {
        //         return Inertia::render('RoleMahasiswa/Quiz');
        //     });
        //     Route::get('/quiz/start', function () {
        //         return Inertia::render('RoleMahasiswa/QuizStart');
        //     });
        // });

        Route::prefix('evaluasi')->group(function () {
            Route::get('/bank-soal', [DosenQuizController::class, 'index']);
            Route::get('/bank-soal/create', [DosenQuizController::class, 'create']);
            Route::post('/bank-soal', [DosenQuizController::class, 'store']);
            Route::get('/bank-soal/{quiz}/edit', [DosenQuizController::class, 'edit']);
            Route::put('/bank-soal/{quiz}', [DosenQuizController::class, 'update']);
            Route::delete('/bank-soal/{quiz}', [DosenQuizController::class, 'destroy']);

            // Quiz questions (soal)
            Route::post('/bank-soal/{quiz}/soal', [DosenQuizQuestionController::class, 'store']);
            Route::put('/bank-soal/{quiz}/soal/{question}', [DosenQuizQuestionController::class, 'update']);
            Route::delete('/bank-soal/{quiz}/soal/{question}', [DosenQuizQuestionController::class, 'destroy']);

            Route::get('/monitoring', [DosenMonitoringQuizController::class, 'index']);
            Route::get('/monitoring/{score}', [DosenMonitoringQuizController::class, 'show']);
        });

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
            // Materi
            Route::get('/materi', [MateriController::class, 'index']);
            Route::get('/materi/create', [MateriController::class, 'create']);
            Route::post('/materi', [MateriController::class, 'store']);
            Route::delete('/materi/{materi}', [MateriController::class, 'destroy']);
            Route::get('/materi/{materi}', [MateriController::class, 'show']);
            Route::get('/materi/{materi}/edit', [MateriController::class, 'edit']);
            Route::put('/materi/{materi}', [MateriController::class, 'update']);

            // LKM-Grafting Settings
            Route::get('/lkm-grafting', [LKMGraftingController::class, 'index']);
            Route::get('/lkm-grafting/settings', [LKMGraftingController::class, 'indexSetting']);
            Route::get('/lkm-grafting/settings/create', [LKMGraftingController::class, 'createSetting']);
            Route::post('/lkm-grafting/settings', [LKMGraftingController::class, 'storeSetting']);
            // Route::delete('/lkm-grafting/settings/{setting}', [LKMGraftingController::class, 'destroySetting']);
            Route::get('/lkm-grafting/settings/{setting}/edit', [LKMGraftingController::class, 'editSetting']);
            Route::put('/lkm-grafting/settings/{setting}', [LKMGraftingController::class, 'updateSetting']);

            // LKM-Grafting Submission
            Route::get('/lkm-grafting/submissions', [LKMGraftingController::class, 'indexSubmission']);
            Route::get('/lkm-grafting/submissions/mahasiswa/{mahasiswaId}', [LKMGraftingController::class, 'showMahasiswaSubmissions']);
            Route::get('/lkm-grafting/submissions/{id}', [LKMGraftingController::class, 'showSubmission']);
            Route::post('/lkm-grafting/submissions/{id}/catatan', [LKMGraftingController::class, 'updateCatatan']);
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
            Route::get('/monitoring', [MonitoringQuizController::class, 'index']);
            Route::get('/monitoring/{score}', [MonitoringQuizController::class, 'show']);
        });

        Route::resource('/mahasiswa', MahasiswaController::class);
        Route::resource('/dosen', DosenController::class);
        Route::get('/settings', [AdminProfileController::class, 'index']);
        Route::put('/settings', [AdminProfileController::class, 'update']);
    });

});
