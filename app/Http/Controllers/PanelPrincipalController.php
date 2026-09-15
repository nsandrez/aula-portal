<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PanelPrincipalController extends Controller
{
    /**
     * Muestra el panel principal del portal escolar según el rol del usuario.
     */
    public function mostrarPanel(Request $request): View
    {
        $usuario = $request->user();

        return view('dashboard', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * Muestra la vista del módulo de calificaciones y notas.
     */
    public function mostrarNotas(Request $request): View
    {
        return view('modulos.notas', [
            'usuario' => $request->user(),
        ]);
    }

    /**
     * Muestra la vista del módulo de control de asistencias.
     */
    public function mostrarAsistencias(Request $request): View
    {
        return view('modulos.asistencias', [
            'usuario' => $request->user(),
        ]);
    }

    /**
     * Muestra la vista de gestión de cursos y asignaturas.
     */
    public function mostrarCursos(Request $request): View
    {
        return view('modulos.cursos', [
            'usuario' => $request->user(),
        ]);
    }

    /**
     * Muestra la vista de matrículas y estudiantes.
     */
    public function mostrarMatriculas(Request $request): View
    {
        return view('modulos.matriculas', [
            'usuario' => $request->user(),
        ]);
    }

    /**
     * Muestra la vista de administración de usuarios y roles.
     */
    public function mostrarUsuarios(Request $request): View
    {
        return view('modulos.usuarios', [
            'usuario' => $request->user(),
        ]);
    }
}
