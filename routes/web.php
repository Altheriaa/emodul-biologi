<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Mahasiswa\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/register', [RegisterController::class, 'index'])->name('register');

Route::middleware(['auth'])->group(function () {

    // MAHASISWA
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.mahasiswa');
        Route::get('/informasi-modul/cpl-cpmk', function () {
            return Inertia::render('RoleMahasiswa/CplCpmk');
        });
        Route::get('/emodul/materi', function () {
            return Inertia::render('RoleMahasiswa/Emodule');
        });
        Route::get('/pembelajaran/materi', function () {
            return Inertia::render('RoleMahasiswa/Materi');
        });
        Route::get('/pembelajaran/materi/flipping-book', function () {
            return Inertia::render('RoleMahasiswa/Flipping');
        });
    });

    // ADMIN
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('dashboard.admin');
    });

});





