<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard', [
        'message' => 'Selamat datang!'
    ]);
}); 

Route::get('/order', function () {
    return Inertia::render('Order', [
        'message' => 'Selamat datang!'
    ]);
}); 