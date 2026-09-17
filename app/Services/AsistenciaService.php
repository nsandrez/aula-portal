<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Asistencia;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AsistenciaService
{
    /**
     * Obtiene el listado de asistencias de un curso en una fecha determinada.
     *
     * @return Collection<int, Asistencia>
     */
    public function obtenerAsistenciaPorCursoYFecha(int $cursoId, string $fecha): Collection
    {
        return Asistencia::query()
            ->with(['estudiante', 'registradoPor'])
            ->where('curso_id', $cursoId)
            ->whereDate('fecha', $fecha)
            ->get();
    }

    /**
     * Arma la lista para pasar asistencia: un registro por cada estudiante regular del curso.
     * Si el día aún no se ha guardado, cada estudiante aparece como "presente" sin guardar.
     * Los registros ya guardados de estudiantes sin matrícula vigente se agregan al final.
     *
     * @return Collection<int, Asistencia>
     */
    public function obtenerListaParaPaseDeLista(int $cursoId, string $fecha): Collection
    {
        $asistenciasGuardadas = $this->obtenerAsistenciaPorCursoYFecha($cursoId, $fecha)->keyBy('estudiante_id');

        $matriculas = Matricula::query()
            ->with('estudiante')
            ->where('curso_id', $cursoId)
            ->where('estado', 'regular')
            ->delAnioVigente()
            ->orderBy('numero_lista')
            ->get();

        $listaMatriculados = $matriculas->map(function (Matricula $matricula) use ($asistenciasGuardadas, $cursoId, $fecha): Asistencia {
            $asistencia = $asistenciasGuardadas->get($matricula->estudiante_id);

            if ($asistencia === null) {
                $asistencia = new Asistencia([
                    'curso_id' => $cursoId,
                    'estudiante_id' => $matricula->estudiante_id,
                    'fecha' => $fecha,
                    'estado' => 'presente',
                ]);
                $asistencia->setRelation('estudiante', $matricula->estudiante);
            }

            return $asistencia;
        });

        $estudiantesMatriculados = $matriculas->pluck('estudiante_id')->all();
        $guardadasSinMatriculaVigente = $asistenciasGuardadas->reject(
            fn (Asistencia $asistencia): bool => in_array($asistencia->estudiante_id, $estudiantesMatriculados, true)
        );

        return $listaMatriculados->concat($guardadasSinMatriculaVigente->values());
    }

    /**
     * Indica si la asistencia de un curso ya fue guardada para la fecha.
     */
    public function estaAsistenciaGuardada(int $cursoId, string $fecha): bool
    {
        return Asistencia::query()
            ->where('curso_id', $cursoId)
            ->whereDate('fecha', $fecha)
            ->exists();
    }

    /**
     * Guarda o actualiza la asistencia de múltiples alumnos para un curso en una fecha.
     *
     * @param  array<int, array{estudiante_id: int, estado: string, hora_llegada?: ?string, observacion?: ?string}>  $registros
     */
    public function registrarAsistenciaDiaria(
        int $cursoId,
        string $fecha,
        array $registros,
        User $registrador
    ): void {
        foreach ($registros as $registro) {
            Asistencia::updateOrCreate(
                [
                    'curso_id' => $cursoId,
                    'estudiante_id' => $registro['estudiante_id'],
                    'fecha' => $fecha,
                ],
                [
                    'estado' => $registro['estado'],
                    'hora_llegada' => $registro['estado'] === 'atraso' ? ($registro['hora_llegada'] ?? null) : null,
                    'observacion' => $registro['estado'] === 'justificado' ? ($registro['observacion'] ?? null) : null,
                    'registrado_por_id' => $registrador->id,
                ]
            );
        }
    }

    /**
     * Calcula métricas y resumen de asistencia para un curso en una fecha específica.
     *
     * @return array{total: int, presentes: int, ausentes: int, atrasos: int, justificados: int, porcentaje_asistencia: float}
     */
    public function calcularResumenCurso(int $cursoId, string $fecha): array
    {
        $asistencias = $this->obtenerAsistenciaPorCursoYFecha($cursoId, $fecha);
        $total = $asistencias->count();

        $presentes = $asistencias->where('estado', 'presente')->count();
        $ausentes = $asistencias->where('estado', 'ausente')->count();
        $atrasos = $asistencias->where('estado', 'atraso')->count();
        $justificados = $asistencias->where('estado', 'justificado')->count();

        $asistentesEfectivos = $presentes + $atrasos;
        $porcentaje = $total > 0 ? round(($asistentesEfectivos / $total) * 100, 1) : 0.0;

        return [
            'total' => $total,
            'presentes' => $presentes,
            'ausentes' => $ausentes,
            'atrasos' => $atrasos,
            'justificados' => $justificados,
            'porcentaje_asistencia' => $porcentaje,
        ];
    }

    /**
     * Obtiene el historial reciente de asistencia de un estudiante.
     *
     * @return Collection<int, Asistencia>
     */
    public function obtenerHistorialEstudiante(User $estudiante, int $limite = 20): Collection
    {
        return Asistencia::query()
            ->with('curso')
            ->where('estudiante_id', $estudiante->id)
            ->orderByDesc('fecha')
            ->limit($limite)
            ->get();
    }

    /**
     * Calcula el porcentaje acumulado anual de asistencia de un estudiante.
     */
    public function calcularPorcentajeEstudiante(User $estudiante): float
    {
        $totalDias = Asistencia::where('estudiante_id', $estudiante->id)->count();

        if ($totalDias === 0) {
            return 100.0;
        }

        $asistidos = Asistencia::where('estudiante_id', $estudiante->id)
            ->whereIn('estado', ['presente', 'atraso'])
            ->count();

        return round(($asistidos / $totalDias) * 100, 1);
    }

    /**
     * Agrega a cada matrícula el porcentaje de asistencia de su estudiante.
     *
     * @param  Collection<int, Matricula>  $matriculas
     * @return Collection<int, Matricula>
     */
    public function agregarPorcentajeAsistencia(Collection $matriculas): Collection
    {
        return $matriculas->each(function (Matricula $matricula): void {
            $matricula->porcentaje_asistencia = $matricula->estudiante
                ? $this->calcularPorcentajeEstudiante($matricula->estudiante)
                : 100.0;
        });
    }
}
