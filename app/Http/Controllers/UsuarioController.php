<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarUsuarioRequest;
use App\Http\Requests\GuardarUsuarioRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Guarda una nueva cuenta de usuario (Exclusivo SuperUsuario).
     */
    public function guardar(GuardarUsuarioRequest $request): RedirectResponse
    {
        $datos = $request->validated();
        $datos['password'] = Hash::make($datos['password']);

        $usuario = User::create($datos);

        return back()->with('exito', "El usuario {$usuario->name} ha sido creado con rol {$usuario->rol->obtenerEtiqueta()}.");
    }

    /**
     * Actualiza una cuenta de usuario (Exclusivo SuperUsuario).
     */
    public function actualizar(ActualizarUsuarioRequest $request, User $usuario): RedirectResponse
    {
        $datos = $request->validated();

        if (! empty($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
        } else {
            unset($datos['password']);
        }

        $usuario->update($datos);

        return back()->with('exito', "La cuenta de {$usuario->name} ha sido actualizada exitosamente.");
    }
}
