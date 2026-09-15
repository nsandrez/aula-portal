<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthController::class, 'mostrarFormularioLogin'])->name('login');
Route::post('/login', [AuthController::class, 'iniciarSesion'])->name('login.post');
Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');
