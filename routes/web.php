<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PanelPrincipalController;
use Illuminate\Support\Facades\Route;

// Rutas para usuarios invitados (login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'mostrarFormularioLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'iniciarSesion'])->name('login.post');
});

// Rutas protegidas para usuarios autenticados
Route::middleware('auth')->group(function () {
    Route::get('/', [PanelPrincipalController::class, 'mostrarPanel'])->name('home');
    Route::post('/logout', [AuthController::class, 'cerrarSesion'])->name('logout');

    // Módulos del sistema escolar
    Route::get('/notas', [PanelPrincipalController::class, 'mostrarNotas'])->name('notas.index');
    Route::get('/asistencias', [PanelPrincipalController::class, 'mostrarAsistencias'])->name('asistencias.index');
    Route::get('/cursos', [PanelPrincipalController::class, 'mostrarCursos'])->name('cursos.index');
    Route::get('/matriculas', [PanelPrincipalController::class, 'mostrarMatriculas'])->name('matriculas.index');
    Route::get('/usuarios', [PanelPrincipalController::class, 'mostrarUsuarios'])->name('usuarios.index');
});
