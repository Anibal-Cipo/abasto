<?php

use Illuminate\Support\Facades\Route;

// Rutas básicas de autenticación
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    // Lógica de login básica
})->name('login.post');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');