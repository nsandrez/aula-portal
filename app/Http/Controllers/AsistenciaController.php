<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\GuardarAsistenciaRequest;
use App\Services\AsistenciaService;
use App\Utils\FormateadorFecha;
use App\Utils\PeriodoEscolar;
use Illuminate\Http\RedirectResponse;

class AsistenciaController extends Controller
{
    /**
     * Registra o actualiza la asistencia diaria de un curso en el día de hoy.
     */
    public function guardar(GuardarAsistenciaRequest $request, AsistenciaService $asistenciaService): RedirectResponse
    {
        $cursoId = (int) $request->input('curso_id');
        $fecha = PeriodoEscolar::fechaDeHoy();
        $asistencias = (array) $request->validated('asistencias', []);

        $asistenciaService->registrarAsistenciaDiaria(
            $cursoId,
            $fecha,
            $asistencias,
            $request->user()
        );

        return redirect()
            ->route('asistencias.index', ['curso_id' => $cursoId])
            ->with('exito', 'Asistencia del '.FormateadorFecha::formatearFecha($fecha).' guardada.');
    }
}
