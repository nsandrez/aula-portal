<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\PanelPrincipalController;
use App\Http\Controllers\UsuarioController;
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

    // CRUD Cursos y Asignaciones
    Route::post('/cursos', [CursoController::class, 'guardar'])->name('cursos.guardar');
    Route::put('/cursos/{curso}', [CursoController::class, 'actualizar'])->name('cursos.actualizar');
    Route::post('/cursos/{curso}/asignaturas', [CursoController::class, 'asociarAsignatura'])->name('cursos.asociar_asignatura');
    Route::delete('/cursos/{curso}/asignaturas/{cursoAsignatura}', [CursoController::class, 'desasociarAsignatura'])->name('cursos.desasociar_asignatura');

    // CRUD Asistencia Diaria
    Route::post('/asistencias', [AsistenciaController::class, 'guardar'])->name('asistencias.guardar');

    // CRUD Matrículas
    Route::post('/matriculas', [MatriculaController::class, 'guardar'])->name('matriculas.guardar');
    Route::post('/matriculas/asociar-apoderado', [MatriculaController::class, 'asociarApoderado'])->name('matriculas.asociar_apoderado');
    Route::put('/matriculas/{matricula}', [MatriculaController::class, 'actualizar'])->name('matriculas.actualizar');

    // CRUD Usuarios (SuperUsuario)
    Route::post('/usuarios', [UsuarioController::class, 'guardar'])->name('usuarios.guardar');
    Route::put('/usuarios/{usuario}', [UsuarioController::class, 'actualizar'])->name('usuarios.actualizar');
});
