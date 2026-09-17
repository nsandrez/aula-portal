<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\RolUsuario;
use App\Models\User;
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
}
