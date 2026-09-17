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
            ->delAnioVigente()
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

    /**
     * Crea un nuevo curso escolar.
     *
     * @param  array<string, mixed>  $datos
     */
    public function crearCurso(array $datos): Curso
    {
        return Curso::create($datos);
    }

    /**
     * Actualiza los datos de un curso escolar.
     *
     * @param  array<string, mixed>  $datos
     */
    public function actualizarCurso(Curso $curso, array $datos): Curso
    {
        $curso->update($datos);

        return $curso;
    }

    /**
     * Asocia una asignatura a un curso a partir de los datos validados del formulario.
     *
     * @param  array{asignatura_id: int|string, docente_id?: int|string|null, horas_semanales?: int|string|null}  $datos
     */
    public function asociarAsignaturaDesdeFormulario(Curso $curso, array $datos): CursoAsignatura
    {
        $asignatura = Asignatura::findOrFail((int) $datos['asignatura_id']);
        $docente = ! empty($datos['docente_id']) ? User::find((int) $datos['docente_id']) : null;
        $horasSemanales = (int) ($datos['horas_semanales'] ?? 4);

        return $this->asociarAsignaturaACurso($curso, $asignatura, $docente, $horasSemanales)
            ->load('asignatura');
    }

    /**
     * Quita una asignatura de un curso y devuelve su nombre para el mensaje de confirmación.
     */
    public function desasociarAsignatura(CursoAsignatura $cursoAsignatura): string
    {
        $nombreAsignatura = $cursoAsignatura->asignatura->nombre ?? 'Asignatura';
        $cursoAsignatura->delete();

        return $nombreAsignatura;
    }

    /**
     * Lista los cursos ordenados por nombre.
     *
     * @return Collection<int, Curso>
     */
    public function obtenerCursosOrdenados(): Collection
    {
        return Curso::query()->orderBy('nombre')->get();
    }

    /**
     * Lista el catálogo de asignaturas ordenado por nombre.
     *
     * @return Collection<int, Asignatura>
     */
    public function obtenerCatalogoAsignaturas(): Collection
    {
        return Asignatura::query()->orderBy('nombre')->get();
    }

    /**
     * Obtiene los pupilos del apoderado matriculados en el año vigente.
     *
     * @param  array<int, string>  $relaciones
     * @return Collection<int, Matricula>
     */
    public function obtenerPupilosDelAnio(User $apoderado, array $relaciones = ['estudiante', 'curso']): Collection
    {
        return $apoderado->pupilosMatriculados()
            ->with($relaciones)
            ->delAnioVigente()
            ->get();
    }

    /**
     * Elige el pupilo pedido por el apoderado o, si no existe, el primero de la lista.
     *
     * @param  Collection<int, Matricula>  $pupilos
     */
    public function seleccionarPupilo(Collection $pupilos, int|string|null $estudianteId): ?Matricula
    {
        if ($estudianteId) {
            $pupiloSeleccionado = $pupilos->firstWhere('estudiante_id', (int) $estudianteId);

            if ($pupiloSeleccionado !== null) {
                return $pupiloSeleccionado;
            }
        }

        return $pupilos->first();
    }
}
