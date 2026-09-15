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
        $pupilos = collect();

        if ($usuario?->esEstudiante()) {
            $matriculaEstudiante = $academicoService->obtenerMatriculaVigente($usuario);
            $porcentajeAsistencia = $asistenciaService->calcularPorcentajeEstudiante($usuario);
        } elseif ($usuario?->esApoderado()) {
            $pupilos = $usuario->pupilosMatriculados()
                ->with(['estudiante', 'curso.profesorJefe'])
                ->where('anio', 2026)
                ->get()
                ->map(function (Matricula $matricula) use ($asistenciaService) {
                    if ($matricula->estudiante) {
                        $matricula->porcentaje_asistencia = $asistenciaService->calcularPorcentajeEstudiante($matricula->estudiante);
                    } else {
                        $matricula->porcentaje_asistencia = 100.0;
                    }

                    return $matricula;
                });
        }

        return view('dashboard', [
            'usuario' => $usuario,
            'cursos' => $cursos,
            'totalEstudiantes' => $totalEstudiantes,
            'matriculaEstudiante' => $matriculaEstudiante,
            'porcentajeAsistencia' => $porcentajeAsistencia,
            'pupilos' => $pupilos,
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
        $pupilos = collect();
        $pupiloSeleccionado = null;

        if ($usuario?->esEstudiante()) {
            $matriculaEstudiante = $academicoService->obtenerMatriculaVigente($usuario);
        } elseif ($usuario?->esApoderado()) {
            $pupilos = $usuario->pupilosMatriculados()
                ->with(['estudiante', 'curso.cursoAsignaturas.asignatura', 'curso.cursoAsignaturas.docente'])
                ->where('anio', 2026)
                ->get();

            $pupiloId = $request->query('pupilo_id');
            if ($pupiloId) {
                $pupiloSeleccionado = $pupilos->firstWhere('estudiante_id', (int) $pupiloId);
            }
            if (! $pupiloSeleccionado && $pupilos->isNotEmpty()) {
                $pupiloSeleccionado = $pupilos->first();
            }
        }

        return view('modulos.notas', [
            'usuario' => $usuario,
            'cursos' => $cursos,
            'matriculaEstudiante' => $matriculaEstudiante,
            'pupilos' => $pupilos,
            'pupiloSeleccionado' => $pupiloSeleccionado,
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

        // Si es estudiante o apoderado, obtenemos el historial individual
        $historialEstudiante = null;
        $porcentajeEstudiante = 100.0;
        $pupilos = collect();
        $pupiloSeleccionado = null;
        $asistenciaHoyPupilo = null;

        if ($usuario?->esEstudiante()) {
            $historialEstudiante = $asistenciaService->obtenerHistorialEstudiante($usuario);
            $porcentajeEstudiante = $asistenciaService->calcularPorcentajeEstudiante($usuario);
        } elseif ($usuario?->esApoderado()) {
            $pupilos = $usuario->pupilosMatriculados()
                ->with(['estudiante', 'curso'])
                ->where('anio', 2026)
                ->get();

            $pupiloId = $request->query('pupilo_id');
            if ($pupiloId) {
                $pupiloSeleccionado = $pupilos->firstWhere('estudiante_id', (int) $pupiloId);
            }
            if (! $pupiloSeleccionado && $pupilos->isNotEmpty()) {
                $pupiloSeleccionado = $pupilos->first();
            }

            if ($pupiloSeleccionado && $pupiloSeleccionado->estudiante) {
                $historialEstudiante = $asistenciaService->obtenerHistorialEstudiante($pupiloSeleccionado->estudiante);
                $porcentajeEstudiante = $asistenciaService->calcularPorcentajeEstudiante($pupiloSeleccionado->estudiante);
                $asistenciaHoyPupilo = $historialEstudiante->firstWhere('fecha', $fecha)
                    ?? $historialEstudiante->firstWhere('fecha', '2026-09-15')
                    ?? $historialEstudiante->first();
            }
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
            'pupilos' => $pupilos,
            'pupiloSeleccionado' => $pupiloSeleccionado,
            'asistenciaHoyPupilo' => $asistenciaHoyPupilo,
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
