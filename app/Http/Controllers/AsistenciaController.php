<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GuardarAsistenciaRequest;
use App\Services\AsistenciaService;
use App\Utils\FormateadorFecha;
use Illuminate\Http\RedirectResponse;

class AsistenciaController extends Controller
{
    /**
     * Registra o actualiza la asistencia diaria de un curso en una fecha determinada.
     */
    public function guardar(GuardarAsistenciaRequest $request, AsistenciaService $asistenciaService): RedirectResponse
    {
        $cursoId = (int) $request->input('curso_id');
        $fecha = (string) $request->input('fecha');
        $asistencias = (array) $request->input('asistencias', []);

        $asistenciaService->registrarAsistenciaDiaria(
            $cursoId,
            $fecha,
            $asistencias,
            $request->user()
        );

        return redirect()
            ->route('asistencias.index', ['curso_id' => $cursoId, 'fecha' => $fecha])
            ->with('exito', 'Asistencia del '.FormateadorFecha::formatearFecha($fecha).' guardada.');
    }
}
