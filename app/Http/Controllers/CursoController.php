<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarCursoRequest;
use App\Http\Requests\AsociarAsignaturaRequest;
use App\Http\Requests\GuardarCursoRequest;
use App\Models\Asignatura;
use App\Models\Curso;
use App\Models\CursoAsignatura;
use App\Models\User;
use App\Services\AcademicoService;
use Illuminate\Http\RedirectResponse;

class CursoController extends Controller
{
    /**
     * Guarda un nuevo curso escolar.
     */
    public function guardar(GuardarCursoRequest $request): RedirectResponse
    {
        $curso = Curso::create($request->validated());

        return back()->with('exito', "El curso {$curso->nombre} ha sido creado exitosamente.");
    }

    /**
     * Actualiza los datos de un curso (Exclusivo SuperUsuario).
     */
    public function actualizar(ActualizarCursoRequest $request, Curso $curso): RedirectResponse
    {
        $curso->update($request->validated());

        return back()->with('exito', "El curso {$curso->nombre} ha sido actualizado correctamente.");
    }

    /**
     * Asocia una asignatura a un curso escolar con su docente y carga horaria.
     */
    public function asociarAsignatura(
        AsociarAsignaturaRequest $request,
        Curso $curso,
        AcademicoService $academicoService
    ): RedirectResponse {
        $asignatura = Asignatura::findOrFail((int) $request->input('asignatura_id'));
        $docenteId = $request->input('docente_id');
        $docente = $docenteId ? User::find((int) $docenteId) : null;
        $horas = (int) $request->input('horas_semanales', 4);

        $academicoService->asociarAsignaturaACurso($curso, $asignatura, $docente, $horas);

        return back()->with('exito', "La asignatura {$asignatura->nombre} ha sido asociada a {$curso->nombre}.");
    }

    /**
     * Desvincula una asignatura de un curso (Exclusivo SuperUsuario).
     */
    public function desasociarAsignatura(Curso $curso, CursoAsignatura $cursoAsignatura): RedirectResponse
    {
        if (! auth()->user()?->esSuperUsuario()) {
            abort(403, 'Acción reservada para SuperUsuario.');
        }

        $nombreAsignatura = $cursoAsignatura->asignatura->nombre ?? 'Asignatura';
        $cursoAsignatura->delete();

        return back()->with('exito', "Se ha desvinculado {$nombreAsignatura} del curso {$curso->nombre}.");
    }
}
