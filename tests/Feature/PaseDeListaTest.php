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

        $this->travelTo(now()->parse('2026-09-17 15:00:00'));

        $this->actingAs($docente)
            ->get(route('asistencias.index', ['curso_id' => $curso->id]))
            ->assertOk()
            ->assertSee('Amanda Pérez')
            ->assertSee('Bruno Díaz')
            ->assertDontSee('Carla Retirada');
    }

    public function test_solo_se_puede_guardar_la_asistencia_del_dia_de_hoy(): void
    {
        $this->travelTo(now()->parse('2026-09-16 15:00:00'));
        [$docente, $curso, $estudiante] = $this->crearCursoConEstudiante();

        $this->actingAs($docente)
            ->post(route('asistencias.guardar'), [
                'curso_id' => $curso->id,
                'fecha' => '2026-09-10',
                'asistencias' => [['estudiante_id' => $estudiante->id, 'estado' => 'presente']],
            ])
            ->assertSessionHasErrors(['fecha' => 'Solo se puede pasar asistencia del día de hoy.']);

        $this->actingAs($docente)
            ->post(route('asistencias.guardar'), [
                'curso_id' => $curso->id,
                'asistencias' => [['estudiante_id' => $estudiante->id, 'estado' => 'presente']],
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('asistencias', ['estudiante_id' => $estudiante->id, 'fecha' => '2026-09-16']);
        $this->assertDatabaseMissing('asistencias', ['fecha' => '2026-09-10']);
    }

    public function test_justificado_exige_motivo_y_atraso_exige_hora(): void
    {
        $this->travelTo(now()->parse('2026-09-16 15:00:00'));
        [$docente, $curso, $estudiante] = $this->crearCursoConEstudiante();

        $this->actingAs($docente)
            ->post(route('asistencias.guardar'), [
                'curso_id' => $curso->id,
                'asistencias' => [
                    ['estudiante_id' => $estudiante->id, 'estado' => 'justificado', 'observacion' => ''],
                ],
            ])
            ->assertSessionHasErrors(['asistencias.0.observacion' => 'Escribe el motivo de cada inasistencia justificada.']);

        $this->actingAs($docente)
            ->post(route('asistencias.guardar'), [
                'curso_id' => $curso->id,
                'asistencias' => [
                    ['estudiante_id' => $estudiante->id, 'estado' => 'atraso'],
                ],
            ])
            ->assertSessionHasErrors(['asistencias.0.hora_llegada']);

        $this->actingAs($docente)
            ->post(route('asistencias.guardar'), [
                'curso_id' => $curso->id,
                'asistencias' => [
                    ['estudiante_id' => $estudiante->id, 'estado' => 'justificado', 'observacion' => 'Certificado médico', 'hora_llegada' => '08:30'],
                ],
            ])
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('asistencias', [
            'estudiante_id' => $estudiante->id,
            'estado' => 'justificado',
            'observacion' => 'Certificado médico',
            'hora_llegada' => null,
        ]);
    }

    public function test_vista_de_asistencia_muestra_la_hora_actual_y_no_permite_elegir_fecha(): void
    {
        $this->travelTo(now()->parse('2026-09-16 13:05:00'));
        [$docente, $curso] = $this->crearCursoConEstudiante();

        $this->actingAs($docente)
            ->get(route('asistencias.index', ['curso_id' => $curso->id]))
            ->assertOk()
            ->assertSee('Hora actual')
            ->assertSee('10:05')
            ->assertSee('Miércoles 16 de septiembre de 2026')
            ->assertDontSee('type="date"', false)
            ->assertSee('Motivo de la justificación');
    }

    /**
     * @return array{0: User, 1: Curso, 2: User}
     */
    private function crearCursoConEstudiante(): array
    {
        $docente = User::factory()->create(['rol' => RolUsuario::Docente]);
        $curso = Curso::create(['nombre' => '5° Básico A', 'nivel' => 'Enseñanza Básica', 'anio' => 2026]);
        $estudiante = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Elena Muñoz']);
        Matricula::create(['estudiante_id' => $estudiante->id, 'curso_id' => $curso->id, 'numero_lista' => 1, 'anio' => 2026, 'estado' => 'regular']);

        return [$docente, $curso, $estudiante];
    }
}
