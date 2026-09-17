<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RolUsuario;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use App\Services\AsistenciaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaseDeListaTest extends TestCase
{
    use RefreshDatabase;

    public function test_lista_incluye_a_estudiantes_regulares_aunque_el_dia_no_este_guardado(): void
    {
        $docente = User::factory()->create(['rol' => RolUsuario::Docente]);
        $curso = Curso::create(['nombre' => '4° Básico A', 'nivel' => 'Enseñanza Básica', 'anio' => 2026]);
        $primero = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Amanda Pérez']);
        $segundo = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Bruno Díaz']);
        $retirado = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Carla Retirada']);

        Matricula::create(['estudiante_id' => $segundo->id, 'curso_id' => $curso->id, 'numero_lista' => 2, 'anio' => 2026, 'estado' => 'regular']);
        Matricula::create(['estudiante_id' => $primero->id, 'curso_id' => $curso->id, 'numero_lista' => 1, 'anio' => 2026, 'estado' => 'regular']);
        Matricula::create(['estudiante_id' => $retirado->id, 'curso_id' => $curso->id, 'numero_lista' => 3, 'anio' => 2026, 'estado' => 'retirado']);

        $servicio = new AsistenciaService;
        $servicio->registrarAsistenciaDiaria($curso->id, '2026-09-16', [
            ['estudiante_id' => $segundo->id, 'estado' => 'ausente'],
        ], $docente);

        $lista = $servicio->obtenerListaParaPaseDeLista($curso->id, '2026-09-16');

        $this->assertSame([$primero->id, $segundo->id], $lista->pluck('estudiante_id')->all());
        $this->assertSame(['presente', 'ausente'], $lista->pluck('estado')->all());
        $this->assertFalse($lista->first()->exists);
        $this->assertTrue($servicio->estaAsistenciaGuardada($curso->id, '2026-09-16'));
        $this->assertFalse($servicio->estaAsistenciaGuardada($curso->id, '2026-09-17'));

        $this->actingAs($docente)
            ->get(route('asistencias.index', ['curso_id' => $curso->id, 'fecha' => '2026-09-17']))
            ->assertOk()
            ->assertSee('Amanda Pérez')
            ->assertSee('Bruno Díaz')
            ->assertDontSee('Carla Retirada');
    }
}
