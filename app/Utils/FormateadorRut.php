<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * Utilidades para limpiar, validar y dar formato al RUT chileno.
 */
final class FormateadorRut
{
    /**
     * Elimina puntos, guiones y espacios del RUT y lo deja en mayúsculas.
     */
    public static function limpiarRut(string $rut): string
    {
        return strtoupper(preg_replace('/[^0-9kK]/', '', $rut) ?? '');
    }

    /**
     * Valida el RUT mediante el algoritmo de Módulo 11.
     */
    public static function esRutValido(string $rut): bool
    {
        $rutLimpio = self::limpiarRut($rut);

        if (strlen($rutLimpio) < 8 || strlen($rutLimpio) > 9) {
            return false;
        }

        $cuerpo = substr($rutLimpio, 0, -1);
        $digitoVerificador = substr($rutLimpio, -1);

        if (! ctype_digit($cuerpo)) {
            return false;
        }

        return $digitoVerificador === self::calcularDigitoVerificador($cuerpo);
    }

    /**
     * Calcula el dígito verificador para el cuerpo numérico de un RUT.
     */
    public static function calcularDigitoVerificador(string $cuerpo): string
    {
        $suma = 0;
        $multiplicador = 2;

        for ($posicion = strlen($cuerpo) - 1; $posicion >= 0; $posicion--) {
            $suma += (int) $cuerpo[$posicion] * $multiplicador;
            $multiplicador = $multiplicador === 7 ? 2 : $multiplicador + 1;
        }

        $resto = 11 - ($suma % 11);

        return match ($resto) {
            11 => '0',
            10 => 'K',
            default => (string) $resto,
        };
    }

    /**
     * Da formato de lectura al RUT (ej. 12345678-5 o 12.345.678-5).
     */
    public static function formatearRut(?string $rut, bool $conPuntos = false): string
    {
        $rutLimpio = self::limpiarRut((string) $rut);

        if (strlen($rutLimpio) < 2) {
            return $rutLimpio;
        }

        $cuerpo = substr($rutLimpio, 0, -1);
        $digitoVerificador = substr($rutLimpio, -1);

        if ($conPuntos) {
            $cuerpo = number_format((int) $cuerpo, 0, ',', '.');
        }

        return "{$cuerpo}-{$digitoVerificador}";
    }
}
