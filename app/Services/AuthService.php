<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Utils\FormateadorRut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Determina si el identificador tiene formato de correo electrónico.
     */
    public function esCorreo(string $identificador): bool
    {
        return (bool) filter_var($identificador, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Intenta autenticar un usuario usando correo electrónico o RUT chileno.
     */
    public function autenticar(string $identificador, string $password, bool $recordar = false): bool
    {
        $limpio = trim($identificador);

        if ($this->esCorreo($limpio)) {
            return Auth::attempt([
                'email' => $limpio,
                'password' => $password,
            ], $recordar);
        }

        $rutLimpio = FormateadorRut::limpiarRut($limpio);

        if (! empty($rutLimpio)) {
            $usuario = User::query()
                ->where('rut', $rutLimpio)
                ->orWhere('rut', $limpio)
                ->orWhereRaw("UPPER(REPLACE(REPLACE(REPLACE(COALESCE(rut, ''), '.', ''), '-', ''), ' ', '')) = ?", [$rutLimpio])
                ->first();

            if ($usuario !== null) {
                return Auth::attempt([
                    'id' => $usuario->id,
                    'password' => $password,
                ], $recordar);
            }
        }

        return false;
    }

    /**
     * Cierra la sesión activa del usuario e invalida la sesión HTTP.
     */
    public function cerrarSesion(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
