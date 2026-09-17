<?php

declare(strict_types=1);

namespace App\Utils;

/**
 * Datos del periodo escolar vigente.
 */
final class PeriodoEscolar
{
    /**
     * Año escolar vigente, configurable con la variable ANIO_ESCOLAR.
     */
    public static function anioVigente(): int
    {
        return (int) config('app.anio_escolar', (int) date('Y'));
    }

    /**
     * Fecha de hoy en formato aaaa-mm-dd.
     */
    public static function fechaDeHoy(): string
    {
        return now()->toDateString();
    }
}
