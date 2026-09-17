<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarUsuarioRequest;
use App\Http\Requests\GuardarUsuarioRequest;
use App\Models\User;
use App\Services\UsuarioService;
use Illuminate\Http\RedirectResponse;

class UsuarioController extends Controller
{
    public function __construct(private UsuarioService $usuarioService) {}

    /**
     * Guarda una nueva cuenta de usuario (Exclusivo SuperUsuario).
     */
    public function guardar(GuardarUsuarioRequest $request): RedirectResponse
    {
        $usuario = $this->usuarioService->crearUsuario($request->validated());

        return back()->with('exito', "Se creó la cuenta de {$usuario->name} como {$usuario->rol->obtenerEtiqueta()}.");
    }

    /**
     * Actualiza una cuenta de usuario (Exclusivo SuperUsuario).
     */
    public function actualizar(ActualizarUsuarioRequest $request, User $usuario): RedirectResponse
    {
        $usuario = $this->usuarioService->actualizarUsuario($usuario, $request->validated());

        return back()->with('exito', "La cuenta de {$usuario->name} fue actualizada.");
    }
}
