<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\RolUsuario;
use App\Models\User;
use App\Utils\FormateadorRut;
use App\Utils\PeriodoEscolar;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    /**
     * Crea una cuenta de usuario con su contraseña cifrada.
     *
     * @param  array<string, mixed>  $datos
     */
    public function crearUsuario(array $datos): User
    {
        $datos['password'] = Hash::make((string) $datos['password']);

        return User::create($datos);
    }

    /**
     * Actualiza una cuenta; la contraseña solo cambia si se envía una nueva.
     *
     * @param  array<string, mixed>  $datos
     */
    public function actualizarUsuario(User $usuario, array $datos): User
    {
        if (! empty($datos['password'])) {
            $datos['password'] = Hash::make((string) $datos['password']);
        } else {
            unset($datos['password']);
        }

        $usuario->update($datos);

        return $usuario;
    }

    /**
     * Lista todos los usuarios ordenados por nombre.
     *
     * @return Collection<int, User>
     */
    public function obtenerUsuariosOrdenados(): Collection
    {
        return User::query()->orderBy('name')->get();
    }

    /**
     * Lista los usuarios de un rol ordenados por nombre.
     *
     * @return Collection<int, User>
     */
    public function obtenerUsuariosPorRol(RolUsuario $rol): Collection
    {
        return User::query()->where('rol', $rol)->orderBy('name')->get();
    }

    /**
     * Busca apoderados por RUT (con o sin formato) o por nombre (nombre y primer o segundo apellido).
     *
     * @return array<int, array{id: int, nombre: string, rut: string, email: string, pupilos_count: int, pupilos: array<int, string>}>
     */
    public function buscarApoderados(string $busqueda, int $limite = 10): array
    {
        $texto = trim($busqueda);
        $rutLimpio = FormateadorRut::limpiarRut($texto);
        $buscaPorRut = preg_match('/^\d{7,9}[0-9kK]?$/', $rutLimpio) === 1;

        $textoNormalizado = User::normalizarNombre($texto);
        $palabras = array_values(array_filter(
            preg_split('/\s+/', $textoNormalizado) ?: [],
            fn (string $p): bool => mb_strlen($p) >= 2
        ));

        // Para evitar búsquedas masivas no indexadas, se exige RUT completo o nombre + apellido
        if (! $buscaPorRut && count($palabras) < 2) {
            return [];
        }

        return User::query()
            ->where('rol', RolUsuario::Apoderado)
            ->where(function (Builder $consulta) use ($texto, $palabras, $rutLimpio, $buscaPorRut): void {
                $consulta->where(function (Builder $sub) use ($palabras, $texto): void {
                    if (! empty($palabras)) {
                        foreach ($palabras as $palabra) {
                            $sub->where('name', 'like', '%'.$palabra.'%');
                        }
                    } else {
                        $sub->where('name', 'like', '%'.$texto.'%');
                    }
                });

                if ($buscaPorRut) {
                    $consulta->orWhereRaw(
                        "UPPER(REPLACE(REPLACE(REPLACE(COALESCE(rut, ''), '.', ''), '-', ''), ' ', '')) LIKE ?",
                        [$rutLimpio.'%']
                    );
                }
            })
            ->with(['pupilosMatriculados.estudiante'])
            ->orderBy('name')
            ->limit($limite)
            ->get()
            ->map(function (User $apoderado): array {
                $pupilosActuales = $apoderado->pupilosMatriculados
                    ->where('anio', PeriodoEscolar::anioVigente());

                return [
                    'id' => $apoderado->id,
                    'nombre' => (string) $apoderado->name,
                    'rut' => FormateadorRut::formatearRut($apoderado->rut),
                    'email' => (string) $apoderado->email,
                    'pupilos_count' => $pupilosActuales->count(),
                    'pupilos' => $pupilosActuales
                        ->map(fn ($m) => (string) $m->estudiante?->name)
                        ->filter()
                        ->values()
                        ->all(),
                ];
            })
            ->all();
    }
}
