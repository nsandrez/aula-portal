<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarMatriculaRequest;
use App\Http\Requests\AsociarApoderadoRequest;
use App\Http\Requests\BuscarEstudiantesRequest;
use App\Http\Requests\GuardarMatriculaRequest;
use App\Models\Matricula;
use App\Models\User;
use App\Services\MatriculaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class MatriculaController extends Controller
{
    public function __construct(private MatriculaService $matriculaService) {}

    /**
     * Registra la matrícula de un estudiante en un curso (existente o nuevo).
     */
    public function guardar(GuardarMatriculaRequest $request): RedirectResponse
    {
        $matricula = $this->matriculaService->registrarMatricula($request->validated());

        return back()->with(
            'exito',
            "{$matricula->estudiante->name} quedó matriculado(a) en {$matricula->curso->nombre} con el número de lista {$matricula->numero_lista}."
        );
    }

    /**
     * Actualiza la matrícula de un estudiante (Exclusivo SuperUsuario).
     */
    public function actualizar(ActualizarMatriculaRequest $request, Matricula $matricula): RedirectResponse
    {
        $matricula = $this->matriculaService->actualizarMatricula($matricula, $request->validated());

        return back()->with('exito', "La matrícula de {$matricula->estudiante->name} fue actualizada.");
    }

    /**
     * Devuelve los estudiantes matriculados que coinciden con un RUT o nombre.
     */
    public function buscarEstudiantes(BuscarEstudiantesRequest $request): JsonResponse
    {
        return response()->json([
            'estudiantes' => $this->matriculaService->buscarEstudiantesMatriculados((string) $request->validated('busqueda')),
        ]);
    }

    /**
     * Asocia uno o múltiples estudiantes (pupilos) a un apoderado.
     */
    public function asociarApoderado(AsociarApoderadoRequest $request): RedirectResponse
    {
        $apoderado = User::findOrFail((int) $request->validated('apoderado_id'));
        $cantidad = $this->matriculaService->asociarApoderadoAPupilos($apoderado, $request->validated('estudiante_ids'));

        return back()->with('exito', "Se vincularon {$cantidad} estudiante(s) a {$apoderado->name}.");
    }
}
