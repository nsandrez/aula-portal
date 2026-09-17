<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarCursoRequest;
use App\Http\Requests\AsociarAsignaturaRequest;
use App\Http\Requests\GuardarCursoRequest;
use App\Models\Curso;
use App\Models\CursoAsignatura;
use App\Services\AcademicoService;
use Illuminate\Http\RedirectResponse;

class CursoController extends Controller
{
    public function __construct(private AcademicoService $academicoService) {}

    /**
     * Guarda un nuevo curso escolar.
     */
    public function guardar(GuardarCursoRequest $request): RedirectResponse
    {
        $curso = $this->academicoService->crearCurso($request->validated());

        return back()->with('exito', "Se creó el curso {$curso->nombre}.");
    }

    /**
     * Actualiza los datos de un curso (Exclusivo SuperUsuario).
     */
    public function actualizar(ActualizarCursoRequest $request, Curso $curso): RedirectResponse
    {
        $curso = $this->academicoService->actualizarCurso($curso, $request->validated());

        return back()->with('exito', "El curso {$curso->nombre} fue actualizado.");
    }

    /**
     * Asocia una asignatura a un curso escolar con su docente y carga horaria.
     */
    public function asociarAsignatura(AsociarAsignaturaRequest $request, Curso $curso): RedirectResponse
    {
        $cursoAsignatura = $this->academicoService->asociarAsignaturaDesdeFormulario($curso, $request->validated());

        return back()->with('exito', "Se agregó {$cursoAsignatura->asignatura->nombre} a {$curso->nombre}.");
    }

    /**
     * Desvincula una asignatura de un curso (Exclusivo SuperUsuario).
     */
    public function desasociarAsignatura(Curso $curso, CursoAsignatura $cursoAsignatura): RedirectResponse
    {
        abort_unless(auth()->user()?->esSuperUsuario(), 403, 'Acción reservada para SuperUsuario.');

        $nombreAsignatura = $this->academicoService->desasociarAsignatura($cursoAsignatura);

        return back()->with('exito', "Se quitó {$nombreAsignatura} del curso {$curso->nombre}.");
    }
}
