<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GuardarMatriculaRequest;
use App\Models\Matricula;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    /**
     * Registra la matrícula de un estudiante en un curso.
     */
    public function guardar(GuardarMatriculaRequest $request): RedirectResponse
    {
        $matricula = Matricula::create($request->validated());

        return back()->with('exito', "El estudiante ha sido matriculado exitosamente en {$matricula->curso->nombre}.");
    }

    /**
     * Actualiza la matrícula de un estudiante (Exclusivo SuperUsuario).
     */
    public function actualizar(Request $request, Matricula $matricula): RedirectResponse
    {
        if (! auth()->user()?->esSuperUsuario()) {
            abort(403, 'Acción reservada para SuperUsuario.');
        }

        $datosValidados = $request->validate([
            'numero_lista' => ['required', 'integer', 'min:1', 'max:60'],
            'estado' => ['required', 'in:regular,retirado'],
            'apoderado_id' => ['nullable', 'exists:users,id'],
        ]);

        $matricula->update($datosValidados);

        return back()->with('exito', "La matrícula de {$matricula->estudiante->name} ha sido actualizada.");
    }
}
