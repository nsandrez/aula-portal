<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RolUsuario;
use App\Services\AcademicoService;
use App\Services\AsistenciaService;
use App\Services\MatriculaService;
use App\Services\UsuarioService;
use App\Utils\PeriodoEscolar;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PanelPrincipalController extends Controller
{
    public function __construct(
        private AcademicoService $academicoService,
        private AsistenciaService $asistenciaService,
    ) {}

    /**
     * Muestra el panel principal del portal escolar según el rol del usuario.
     */
    public function mostrarPanel(Request $request, MatriculaService $matriculaService): View
    {
        $usuario = $request->user();
        $matriculaEstudiante = null;
        $porcentajeAsistencia = 100.0;
        $pupilos = collect();

        if ($usuario?->esEstudiante()) {
            $matriculaEstudiante = $this->academicoService->obtenerMatriculaVigente($usuario);
            $porcentajeAsistencia = $this->asistenciaService->calcularPorcentajeEstudiante($usuario);
        } elseif ($usuario?->esApoderado()) {
            $pupilos = $this->asistenciaService->agregarPorcentajeAsistencia(
                $this->academicoService->obtenerPupilosDelAnio($usuario, ['estudiante', 'curso.profesorJefe'])
            );
        }

        return view('dashboard', [
            'usuario' => $usuario,
            'cursos' => $this->academicoService->obtenerCursosConAsignaturas(),
            'totalEstudiantes' => $matriculaService->contarEstudiantesMatriculados(),
            'matriculaEstudiante' => $matriculaEstudiante,
            'porcentajeAsistencia' => $porcentajeAsistencia,
            'pupilos' => $pupilos,
        ]);
    }

    /**
     * Muestra la vista del módulo de calificaciones y notas.
     */
    public function mostrarNotas(Request $request): View
    {
        $usuario = $request->user();
        $matriculaEstudiante = null;
        $pupilos = collect();
        $pupiloSeleccionado = null;

        if ($usuario?->esEstudiante()) {
            $matriculaEstudiante = $this->academicoService->obtenerMatriculaVigente($usuario);
        } elseif ($usuario?->esApoderado()) {
            $pupilos = $this->academicoService->obtenerPupilosDelAnio($usuario, [
                'estudiante',
                'curso.cursoAsignaturas.asignatura',
                'curso.cursoAsignaturas.docente',
            ]);
            $pupiloSeleccionado = $this->academicoService->seleccionarPupilo($pupilos, $request->query('pupilo_id'));
        }

        return view('modulos.notas', [
            'usuario' => $usuario,
            'cursos' => $this->academicoService->obtenerCursosConAsignaturas(),
            'matriculaEstudiante' => $matriculaEstudiante,
            'pupilos' => $pupilos,
            'pupiloSeleccionado' => $pupiloSeleccionado,
        ]);
    }

    /**
     * Muestra la vista del módulo de control de asistencias día a día.
     */
    public function mostrarAsistencias(Request $request): View
    {
        $usuario = $request->user();
        $fecha = (string) $request->query('fecha', PeriodoEscolar::fechaDeHoy());

        $cursos = $this->academicoService->obtenerCursosOrdenados();
        $cursoSeleccionado = $cursos->firstWhere('id', (int) $request->query('curso_id')) ?? $cursos->first();
        $cursoId = $cursoSeleccionado?->id ?? 0;

        $historialEstudiante = null;
        $porcentajeEstudiante = 100.0;
        $pupilos = collect();
        $pupiloSeleccionado = null;
        $asistenciaHoyPupilo = null;

        if ($usuario?->esEstudiante()) {
            $historialEstudiante = $this->asistenciaService->obtenerHistorialEstudiante($usuario);
            $porcentajeEstudiante = $this->asistenciaService->calcularPorcentajeEstudiante($usuario);
        } elseif ($usuario?->esApoderado()) {
            $pupilos = $this->academicoService->obtenerPupilosDelAnio($usuario);
            $pupiloSeleccionado = $this->academicoService->seleccionarPupilo($pupilos, $request->query('pupilo_id'));

            if ($pupiloSeleccionado?->estudiante) {
                $historialEstudiante = $this->asistenciaService->obtenerHistorialEstudiante($pupiloSeleccionado->estudiante);
                $porcentajeEstudiante = $this->asistenciaService->calcularPorcentajeEstudiante($pupiloSeleccionado->estudiante);
                $asistenciaHoyPupilo = $historialEstudiante->firstWhere('fecha', $fecha) ?? $historialEstudiante->first();
            }
        }

        return view('modulos.asistencias', [
            'usuario' => $usuario,
            'cursos' => $cursos,
            'cursoSeleccionado' => $cursoSeleccionado,
            'fecha' => $fecha,
            'asistencias' => $this->asistenciaService->obtenerAsistenciaPorCursoYFecha($cursoId, $fecha),
            'resumen' => $this->asistenciaService->calcularResumenCurso($cursoId, $fecha),
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
    public function mostrarCursos(Request $request, UsuarioService $usuarioService): View
    {
        return view('modulos.cursos', [
            'usuario' => $request->user(),
            'cursos' => $this->academicoService->obtenerCursosConAsignaturas(),
            'docentes' => $usuarioService->obtenerUsuariosPorRol(RolUsuario::Docente),
            'catalogoAsignaturas' => $this->academicoService->obtenerCatalogoAsignaturas(),
        ]);
    }

    /**
     * Muestra la vista de matrículas y estudiantes.
     */
    public function mostrarMatriculas(
        Request $request,
        MatriculaService $matriculaService,
        UsuarioService $usuarioService
    ): View {
        return view('modulos.matriculas', [
            'usuario' => $request->user(),
            'matriculas' => $matriculaService->obtenerMatriculasDelAnio(),
            'estudiantes' => $usuarioService->obtenerUsuariosPorRol(RolUsuario::Estudiante),
            'apoderados' => $usuarioService->obtenerUsuariosPorRol(RolUsuario::Apoderado),
            'cursos' => $this->academicoService->obtenerCursosOrdenados(),
            'anioVigente' => $matriculaService->obtenerAnioVigente(),
        ]);
    }

    /**
     * Muestra la vista de administración de usuarios y roles.
     */
    public function mostrarUsuarios(Request $request, UsuarioService $usuarioService): View
    {
        return view('modulos.usuarios', [
            'usuario' => $request->user(),
            'usuarios' => $usuarioService->obtenerUsuariosOrdenados(),
            'roles' => RolUsuario::cases(),
        ]);
    }
}
