<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\RolUsuario;
use App\Models\Matricula;
use App\Models\User;
use App\Utils\PeriodoEscolar;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MatriculaService
{
    /**
     * Clave inicial que recibe un estudiante registrado sin contraseña.
     */
    public const CLAVE_INICIAL_ESTUDIANTE = 'estudiante2026';

    /**
     * Matricula a un estudiante existente o registra uno nuevo y lo matricula.
     *
     * @param  array<string, mixed>  $datos
     */
    public function registrarMatricula(array $datos): Matricula
    {
        return DB::transaction(function () use ($datos): Matricula {
            $estudianteId = ($datos['tipo_registro'] ?? null) === 'nuevo'
                ? $this->registrarEstudianteNuevo($datos)->id
                : (int) $datos['estudiante_id'];

            return Matricula::create([
                'estudiante_id' => $estudianteId,
                'curso_id' => (int) $datos['curso_id'],
                'apoderado_id' => ! empty($datos['apoderado_id']) ? (int) $datos['apoderado_id'] : null,
                'numero_lista' => (int) $datos['numero_lista'],
                'anio' => (int) $datos['anio'],
                'estado' => 'regular',
            ])->load(['estudiante', 'curso']);
        });
    }

    /**
     * Crea la cuenta de un estudiante nuevo a partir de los datos del formulario de matrícula.
     *
     * @param  array<string, mixed>  $datos
     */
    public function registrarEstudianteNuevo(array $datos): User
    {
        return User::create([
            'name' => (string) $datos['nombre_estudiante'],
            'email' => (string) $datos['email_estudiante'],
            'rut' => ! empty($datos['rut_estudiante']) ? (string) $datos['rut_estudiante'] : null,
            'rol' => RolUsuario::Estudiante,
            'password' => Hash::make((string) ($datos['password_estudiante'] ?? '') ?: self::CLAVE_INICIAL_ESTUDIANTE),
        ]);
    }

    /**
     * Actualiza número de lista, estado y apoderado de una matrícula.
     *
     * @param  array{numero_lista: int, estado: string, apoderado_id?: int|null}  $datos
     */
    public function actualizarMatricula(Matricula $matricula, array $datos): Matricula
    {
        $matricula->update($datos);

        return $matricula->load('estudiante');
    }

    /**
     * Vincula uno o varios estudiantes del año vigente a un apoderado.
     *
     * @param  array<int, int|string>  $estudianteIds
     * @return int Cantidad de estudiantes vinculados.
     */
    public function asociarApoderadoAPupilos(User $apoderado, array $estudianteIds): int
    {
        Matricula::query()
            ->whereIn('estudiante_id', $estudianteIds)
            ->delAnioVigente()
            ->update(['apoderado_id' => $apoderado->id]);

        return count($estudianteIds);
    }

    /**
     * Obtiene las matrículas del año vigente con estudiante, curso y apoderado.
     *
     * @return Collection<int, Matricula>
     */
    public function obtenerMatriculasDelAnio(): Collection
    {
        return Matricula::query()
            ->with(['estudiante', 'curso', 'apoderado'])
            ->delAnioVigente()
            ->get();
    }

    /**
     * Cuenta los estudiantes matriculados en el año vigente.
     */
    public function contarEstudiantesMatriculados(): int
    {
        return Matricula::query()->delAnioVigente()->count();
    }

    /**
     * Año escolar que se propone por defecto al matricular.
     */
    public function obtenerAnioVigente(): int
    {
        return PeriodoEscolar::anioVigente();
    }
}
