<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Limpia un RUT chileno eliminando puntos y guiones, y convirtiendo a mayúsculas.
     */
    public function limpiarRut(string $rut): string
    {
        return strtoupper(preg_replace('/[^0-9kK]/', '', $rut) ?? '');
    }

    /**
     * Valida si un string corresponde a un RUT chileno válido mediante el algoritmo de Módulo 11.
     */
    public function esRutValido(string $rut): bool
    {
        $limpio = $this->limpiarRut($rut);

        if (strlen($limpio) < 8 || strlen($limpio) > 9) {
            return false;
        }

        $cuerpo = substr($limpio, 0, -1);
        $dv = substr($limpio, -1);

        if (! ctype_digit($cuerpo)) {
            return false;
        }

        $suma = 0;
        $multiplo = 2;

        for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
            $suma += (int) $cuerpo[$i] * $multiplo;
            $multiplo = $multiplo === 7 ? 2 : $multiplo + 1;
        }

        $resto = 11 - ($suma % 11);

        $dvEsperado = match ($resto) {
            11 => '0',
            10 => 'K',
            default => (string) $resto,
        };

        return strtoupper($dv) === $dvEsperado;
    }

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

        $rutLimpio = $this->limpiarRut($limpio);

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
