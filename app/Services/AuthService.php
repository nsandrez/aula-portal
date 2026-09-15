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
    public function cleanRut(string $rut): string
    {
        return strtoupper(preg_replace('/[^0-9kK]/', '', $rut) ?? '');
    }

    /**
     * Valida si un string corresponde a un RUT chileno válido mediante el algoritmo de Módulo 11.
     */
    public function isValidRut(string $rut): bool
    {
        $cleaned = $this->cleanRut($rut);

        if (strlen($cleaned) < 8 || strlen($cleaned) > 9) {
            return false;
        }

        $cuerpo = substr($cleaned, 0, -1);
        $dv = substr($cleaned, -1);

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
    public function isEmail(string $identifier): bool
    {
        return (bool) filter_var($identifier, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Intenta autenticar un usuario usando correo electrónico o RUT chileno.
     */
    public function authenticate(string $identifier, string $password, bool $remember = false): bool
    {
        $trimmed = trim($identifier);

        if ($this->isEmail($trimmed)) {
            return Auth::attempt([
                'email' => $trimmed,
                'password' => $password,
            ], $remember);
        }

        $cleanRut = $this->cleanRut($trimmed);

        if (! empty($cleanRut)) {
            $user = User::query()
                ->where('rut', $cleanRut)
                ->orWhere('rut', $trimmed)
                ->orWhereRaw("UPPER(REPLACE(REPLACE(REPLACE(COALESCE(rut, ''), '.', ''), '-', ''), ' ', '')) = ?", [$cleanRut])
                ->first();

            if ($user !== null) {
                return Auth::attempt([
                    'id' => $user->id,
                    'password' => $password,
                ], $remember);
            }
        }

        return false;
    }

    /**
     * Cierra la sesión activa del usuario e invalida la sesión HTTP.
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
