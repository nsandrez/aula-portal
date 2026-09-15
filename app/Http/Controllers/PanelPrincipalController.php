<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RolUsuario;
use App\Models\Asignatura;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use App\Services\AcademicoService;
use App\Services\AsistenciaService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PanelPrincipalController extends Controller
{
    /**
     * Muestra el panel principal del portal escolar según el rol del usuario.
     */
    public function mostrarPanel(
        Request $request,
        AcademicoService $academicoService,
        AsistenciaService $asistenciaService
    ): View {
        $usuario = $request->user();

        $cursos = $academicoService->obtenerCursosConAsignaturas();
        $totalEstudiantes = Matricula::where('anio', 2026)->count();

        // Resumen para estudiante o apoderado
        $matriculaEstudiante = null;
        $porcentajeAsistencia = 100.0;
        if ($usuario?->esEstudiante()) {
            $matriculaEstudiante = $academicoService->obtenerMatriculaVigente($usuario);
            $porcentajeAsistencia = $asistenciaService->calcularPorcentajeEstudiante($usuario);
        }

        return view('dashboard', [
            'usuario' => $usuario,
            'cursos' => $cursos,
            'totalEstudiantes' => $totalEstudiantes,
            'matriculaEstudiante' => $matriculaEstudiante,
            'porcentajeAsistencia' => $porcentajeAsistencia,
        ]);
    }

    /**
     * Muestra la vista del módulo de calificaciones y notas.
     */
    public function mostrarNotas(Request $request, AcademicoService $academicoService): View
    {
        $usuario = $request->user();
        $cursos = $academicoService->obtenerCursosConAsignaturas();
        $matriculaEstudiante = null;

        if ($usuario?->esEstudiante()) {
            $matriculaEstudiante = $academicoService->obtenerMatriculaVigente($usuario);
        }

        return view('modulos.notas', [
            'usuario' => $usuario,
            'cursos' => $cursos,
            'matriculaEstudiante' => $matriculaEstudiante,
        ]);
    }

    /**
     * Muestra la vista del módulo de control de asistencias día a día.
     */
    public function mostrarAsistencias(Request $request, AsistenciaService $asistenciaService): View
    {
        $usuario = $request->user();
        $fecha = (string) $request->query('fecha', '2026-09-15');
        $cursoId = (int) $request->query('curso_id', 1);

        $cursos = Curso::orderBy('nombre')->get();
        $cursoSeleccionado = Curso::find($cursoId) ?? $cursos->first();

        $asistencias = $asistenciaService->obtenerAsistenciaPorCursoYFecha($cursoSeleccionado?->id ?? 1, $fecha);
        $resumen = $asistenciaService->calcularResumenCurso($cursoSeleccionado?->id ?? 1, $fecha);

        // Si es estudiante, obtenemos su historial individual
        $historialEstudiante = null;
        $porcentajeEstudiante = 100.0;
        if ($usuario?->esEstudiante()) {
            $historialEstudiante = $asistenciaService->obtenerHistorialEstudiante($usuario);
            $porcentajeEstudiante = $asistenciaService->calcularPorcentajeEstudiante($usuario);
        }

        return view('modulos.asistencias', [
            'usuario' => $usuario,
            'cursos' => $cursos,
            'cursoSeleccionado' => $cursoSeleccionado,
            'fecha' => $fecha,
            'asistencias' => $asistencias,
            'resumen' => $resumen,
            'historialEstudiante' => $historialEstudiante,
            'porcentajeEstudiante' => $porcentajeEstudiante,
        ]);
    }

    /**
     * Muestra la vista de gestión de cursos y sus asignaturas asociadas.
     */
    public function mostrarCursos(Request $request, AcademicoService $academicoService): View
    {
        $cursos = $academicoService->obtenerCursosConAsignaturas();
        $docentes = User::where('rol', RolUsuario::Docente)->orderBy('name')->get();
        $catalogoAsignaturas = Asignatura::orderBy('nombre')->get();

        return view('modulos.cursos', [
            'usuario' => $request->user(),
            'cursos' => $cursos,
            'docentes' => $docentes,
            'catalogoAsignaturas' => $catalogoAsignaturas,
        ]);
    }

    /**
     * Muestra la vista de matrículas y estudiantes.
     */
    public function mostrarMatriculas(Request $request): View
    {
        $matriculas = Matricula::with(['estudiante', 'curso', 'apoderado'])
            ->where('anio', 2026)
            ->get();

        $estudiantes = User::where('rol', RolUsuario::Estudiante)->orderBy('name')->get();
        $apoderados = User::where('rol', RolUsuario::Apoderado)->orderBy('name')->get();
        $cursos = Curso::orderBy('nombre')->get();

        return view('modulos.matriculas', [
            'usuario' => $request->user(),
            'matriculas' => $matriculas,
            'estudiantes' => $estudiantes,
            'apoderados' => $apoderados,
            'cursos' => $cursos,
        ]);
    }

    /**
     * Muestra la vista de administración de usuarios y roles.
     */
    public function mostrarUsuarios(Request $request): View
    {
        $usuarios = User::orderBy('name')->get();
        $roles = RolUsuario::cases();

        return view('modulos.usuarios', [
            'usuario' => $request->user(),
            'usuarios' => $usuarios,
            'roles' => $roles,
        ]);
    }
}
