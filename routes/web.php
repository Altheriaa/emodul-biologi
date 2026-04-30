<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'message' => 'Selamat datang!'
    ]);
}); 

Route::get('/informasi-modul/cpl-cpmk', function () {
    return Inertia::render('CplCpmk', [
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

Route::get('/login', function () {
    return Inertia::render('Auth/Login');
}); 

Route::get('/register', function () {
    return Inertia::render('Auth/Register');
}); 

Route::get('/pembelajaran/materi/flipping-book', function () {
    return Inertia::render('Flipping');
}); 