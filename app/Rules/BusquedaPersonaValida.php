<?php

declare(strict_types=1);

namespace App\Rules;

use App\Utils\FormateadorRut;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class BusquedaPersonaValida implements ValidationRule
{
    /**
     * Valida que la búsqueda sea un RUT con o sin formato, o bien un nombre que contenga
     * nombre y al menos un apellido (paterno o materno) para evitar búsquedas masivas costosas.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $texto = trim((string) $value);
        $rutLimpio = FormateadorRut::limpiarRut($texto);

        // Si tiene longitud y formato compatible con RUT chileno (mínimo 7 caracteres)
        $esRut = preg_match('/^\d{7,9}[0-9kK]?$/', $rutLimpio) === 1;

        if ($esRut) {
            return;
        }

        // Si es búsqueda por nombre, se exige al menos 2 palabras (nombre + apellido)
        $palabras = array_values(array_filter(
            preg_split('/\s+/', $texto) ?: [],
            fn (string $p): bool => mb_strlen($p) >= 2
        ));

        if (count($palabras) < 2) {
            $fail('Debes ingresar el nombre y al menos un apellido (ej. Carla Pérez), o un RUT completo.');
        }
    }
}
