<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Asignatura;
use App\Models\Curso;
use App\Models\CursoAsignatura;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AcademicoService
{
    /**
     * Obtiene todos los cursos con sus asignaturas asociadas, docentes y profesor jefe.
     *
     * @return Collection<int, Curso>
     */
    public function obtenerCursosConAsignaturas(): Collection
    {
        return Curso::query()
            ->with([
                'profesorJefe',
                'cursoAsignaturas.asignatura',
                'cursoAsignaturas.docente',
                'matriculas.estudiante',
            ])
            ->withCount('matriculas')
            ->orderBy('nombre')
            ->get();
    }

    /**
     * Obtiene las asignaturas que un docente imparte por curso.
     *
     * @return Collection<int, CursoAsignatura>
     */
    public function obtenerAsignaturasPorDocente(User $docente): Collection
    {
        return CursoAsignatura::query()
            ->with(['curso', 'asignatura'])
            ->where('docente_id', $docente->id)
            ->get();
    }

    /**
     * Obtiene la matrícula vigente de un estudiante junto con su curso y asignaturas.
     */
    public function obtenerMatriculaVigente(User $estudiante): ?Matricula
    {
        return Matricula::query()
            ->with([
                'curso.profesorJefe',
                'curso.cursoAsignaturas.asignatura',
                'curso.cursoAsignaturas.docente',
            ])
            ->where('estudiante_id', $estudiante->id)
            ->where('anio', 2026)
            ->first();
    }

    /**
     * Asocia una asignatura a un curso asignando su docente y carga horaria.
     */
    public function asociarAsignaturaACurso(
        Curso $curso,
        Asignatura $asignatura,
        ?User $docente = null,
        int $horasSemanales = 4
    ): CursoAsignatura {
        return CursoAsignatura::updateOrCreate(
            [
                'curso_id' => $curso->id,
                'asignatura_id' => $asignatura->id,
            ],
            [
                'docente_id' => $docente?->id,
                'horas_semanales' => $horasSemanales,
            ]
        );
    }
}
