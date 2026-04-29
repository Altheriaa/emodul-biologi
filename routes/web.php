<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'message' => 'Selamat datang!'
    ]);
}); 

Route::get('/informasi-modul', function () {
    return Inertia::render('InformasiModul', [
        'message' => 'Selamat datang!'
    ]);
}); 


Route::get('/emodul/materi', function () {
    return Inertia::render('Emodule', [
        'message' => 'Selamat datang!'
    ]);
}); 

Route::get('/pembelajaran/materi', function () {
    return Inertia::render('Materi', [
        'message' => 'Selamat datang!'
    ]);
}); 