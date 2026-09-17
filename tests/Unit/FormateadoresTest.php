<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\User;
use App\Utils\FormateadorFecha;
use App\Utils\FormateadorRut;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class FormateadoresTest extends TestCase
{
    public function test_limpiar_rut_quita_puntos_guion_y_espacios(): void
    {
        $this->assertSame('12345678K', FormateadorRut::limpiarRut(' 12.345.678-k '));
    }

    /**
     * @return array<string, array{0: string, 1: bool}>
     */
    public static function rutsDePrueba(): array
    {
        return [
            'rut valido con formato' => ['11.111.111-1', true],
            'rut valido con K' => ['10.000.013-K', true],
            'digito verificador incorrecto' => ['11.111.111-2', false],
            'rut demasiado corto' => ['123-4', false],
            'texto sin numeros' => ['abc', false],
        ];
    }

    #[DataProvider('rutsDePrueba')]
    public function test_es_rut_valido_segun_modulo_11(string $rut, bool $esperado): void
    {
        $this->assertSame($esperado, FormateadorRut::esRutValido($rut));
    }

    public function test_formatear_rut_con_y_sin_puntos(): void
    {
        $this->assertSame('12345678-5', FormateadorRut::formatearRut('12.345.678-5'));
        $this->assertSame('12.345.678-5', FormateadorRut::formatearRut('123456785', conPuntos: true));
        $this->assertSame('', FormateadorRut::formatearRut(null));
    }

    public function test_formatear_fecha_y_porcentaje(): void
    {
        $this->assertSame('15-09-2026', FormateadorFecha::formatearFecha('2026-09-15'));
        $this->assertSame('', FormateadorFecha::formatearFecha(null));
        $this->assertSame('95,5%', FormateadorFecha::formatearPorcentaje(95.46));
    }

    public function test_normalizar_nombre_convierte_a_mayusculas_y_elimina_tildes(): void
    {
        $this->assertSame('MATIAS PEREZ ALVAREZ', User::normalizarNombre('Matías Pérez Álvarez'));
        $this->assertSame('GUILLERMO AGUERO MUÑOZ', User::normalizarNombre('  Guillermo Agüero Muñoz  '));
        $this->assertSame('ANDRES SEBASTIAN OSORES', User::normalizarNombre('ANDRÉS SEBASTIÁN ÓSORES'));
        $this->assertSame('RAUL LEOPOLDO', User::normalizarNombre('Raúl    Leopoldo'));
    }
}
