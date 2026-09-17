<?php

declare(strict_types=1);

namespace App\Utils;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/**
 * Utilidades para mostrar fechas y porcentajes en formato chileno.
 */
final class FormateadorFecha
{
    /**
     * Formatea una fecha como dd-mm-aaaa.
     */
    public static function formatearFecha(CarbonInterface|string|null $fecha): string
    {
        if ($fecha === null || $fecha === '') {
            return '';
        }

        return Carbon::parse($fecha)->format('d-m-Y');
    }

    /**
     * Formatea una fecha en palabras (ej. "martes 15 de septiembre de 2026").
     */
    public static function formatearFechaLarga(CarbonInterface|string|null $fecha): string
    {
        if ($fecha === null || $fecha === '') {
            return '';
        }

        return Carbon::parse($fecha)->locale('es')->translatedFormat('l j \d\e F \d\e Y');
    }

    /**
     * Formatea un porcentaje con coma decimal (ej. 95,5%).
     */
    public static function formatearPorcentaje(float|int|null $valor, int $decimales = 1): string
    {
        return number_format((float) ($valor ?? 0), $decimales, ',', '.').'%';
    }
}
