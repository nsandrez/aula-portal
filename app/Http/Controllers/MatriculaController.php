<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RolUsuario;
use App\Http\Requests\GuardarMatriculaRequest;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MatriculaController extends Controller
{
    /**
     * Registra la matrícula de un estudiante en un curso (existente o nuevo).
     */
    public function guardar(GuardarMatriculaRequest $request): RedirectResponse
    {
        $estudianteId = $request->input('estudiante_id');

        // Si se seleccionó registrar un nuevo alumno
        if ($request->input('tipo_registro') === 'nuevo') {
            $nuevoEstudiante = User::create([
                'name' => (string) $request->input('nombre_estudiante'),
                'email' => (string) $request->input('email_estudiante'),
                'rut' => $request->input('rut_estudiante') ? (string) $request->input('rut_estudiante') : null,
                'rol' => RolUsuario::Estudiante,
                'password' => Hash::make(
                    (string) ($request->input('password_estudiante') ?: 'estudiante2026')
                ),
            ]);

            $estudianteId = $nuevoEstudiante->id;
        }

        $matricula = Matricula::create([
            'estudiante_id' => $estudianteId,
            'curso_id' => (int) $request->input('curso_id'),
            'apoderado_id' => $request->input('apoderado_id') ? (int) $request->input('apoderado_id') : null,
            'numero_lista' => (int) $request->input('numero_lista'),
            'anio' => (int) $request->input('anio'),
            'estado' => 'regular',
        ]);

        return back()->with(
            'exito',
            "El estudiante {$matricula->estudiante->name} ha sido matriculado exitosamente en {$matricula->curso->nombre} (N° lista: {$matricula->numero_lista})."
        );
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
