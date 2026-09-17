<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RolUsuario;
use App\Models\Asignatura;
use App\Models\Curso;
use App\Models\User;
use App\Services\AcademicoService;
use App\Services\AsistenciaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AcademicoYAsistenciaTest extends TestCase
{
    use RefreshDatabase;

    public function test_un_curso_puede_tener_asignaturas_asociadas_con_docente(): void
    {
        $docente = User::factory()->create([
            'name' => 'Profesor Carlos',
            'rol' => RolUsuario::Docente,
        ]);

        $curso = Curso::create([
            'nombre' => '1° Medio A',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
            'profesor_jefe_id' => $docente->id,
        ]);

        $asignatura = Asignatura::create([
            'nombre' => 'Matemáticas',
            'codigo' => 'MAT-101',
        ]);

        $academicoService = new AcademicoService;
        $asociacion = $academicoService->asociarAsignaturaACurso($curso, $asignatura, $docente, 6);

        $this->assertEquals($curso->id, $asociacion->curso_id);
        $this->assertEquals($asignatura->id, $asociacion->asignatura_id);
        $this->assertEquals($docente->id, $asociacion->docente_id);
        $this->assertEquals(6, $asociacion->horas_semanales);

        $this->assertCount(1, $curso->fresh()->asignaturas);
        $this->assertEquals('Matemáticas', $curso->fresh()->asignaturas->first()->nombre);
    }

    public function test_se_puede_registrar_y_resumir_asistencia_diaria_de_un_curso(): void
    {
        $docente = User::factory()->create(['rol' => RolUsuario::Docente]);
        $estudiante1 = User::factory()->create(['rol' => RolUsuario::Estudiante]);
        $estudiante2 = User::factory()->create(['rol' => RolUsuario::Estudiante]);

        $curso = Curso::create([
            'nombre' => '2° Medio B',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
            'profesor_jefe_id' => $docente->id,
        ]);

        $asistenciaService = new AsistenciaService;
        $fecha = '2026-09-15';

        $registros = [
            [
                'estudiante_id' => $estudiante1->id,
                'estado' => 'presente',
                'hora_llegada' => '07:55:00',
                'observacion' => 'Puntual',
            ],
            [
                'estudiante_id' => $estudiante2->id,
                'estado' => 'atraso',
                'hora_llegada' => '08:20:00',
                'observacion' => 'Atraso 20 minutos',
            ],
        ];

        $asistenciaService->registrarAsistenciaDiaria($curso->id, $fecha, $registros, $docente);

        $asistencias = $asistenciaService->obtenerAsistenciaPorCursoYFecha($curso->id, $fecha);
        $this->assertCount(2, $asistencias);

        $resumen = $asistenciaService->calcularResumenCurso($curso->id, $fecha);
        $this->assertEquals(2, $resumen['total']);
        $this->assertEquals(1, $resumen['presentes']);
        $this->assertEquals(1, $resumen['atrasos']);
        $this->assertEquals(0, $resumen['ausentes']);
        $this->assertEquals(100.0, $resumen['porcentaje_asistencia']);
    }

    public function test_vista_cursos_despliega_asignaturas_asociadas(): void
    {
        $docente = User::factory()->create([
            'name' => 'Prof. Rodrigo Sánchez',
            'rol' => RolUsuario::Docente,
        ]);

        $curso = Curso::create([
            'nombre' => '1° Medio A',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
            'profesor_jefe_id' => $docente->id,
        ]);

        $asignatura = Asignatura::create([
            'nombre' => 'Lengua y Literatura',
            'codigo' => 'LEN-101',
        ]);

        (new AcademicoService)->asociarAsignaturaACurso($curso, $asignatura, $docente, 6);

        $admin = User::factory()->create(['rol' => RolUsuario::Administrador]);

        $response = $this->actingAs($admin)->get('/cursos');

        $response->assertStatus(200);
        $response->assertSee('1° Medio A');
        $response->assertSee('Lengua y Literatura');
        $response->assertSee('LEN-101');
        $response->assertSee('Prof. Rodrigo Sánchez');
    }

    public function test_vista_asistencias_despliega_control_dia_a_dia(): void
    {
        $this->travelTo(now()->parse('2026-09-15 15:00:00'));

        $docente = User::factory()->create(['rol' => RolUsuario::Docente]);
        $estudiante = User::factory()->create([
            'name' => 'Sofía Álvarez',
            'rol' => RolUsuario::Estudiante,
        ]);

        $curso = Curso::create([
            'nombre' => '1° Medio A',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
            'profesor_jefe_id' => $docente->id,
        ]);

        (new AsistenciaService)->registrarAsistenciaDiaria(
            $curso->id,
            '2026-09-15',
            [
                [
                    'estudiante_id' => $estudiante->id,
                    'estado' => 'presente',
                    'hora_llegada' => '07:55:00',
                ],
            ],
            $docente
        );

        $response = $this->actingAs($docente)->get('/asistencias?curso_id='.$curso->id);

        $response->assertStatus(200);
        $response->assertSee('Sofía Álvarez');
        $response->assertSee('Presente');
        $response->assertSee('Control de Asistencia del Día a Día');
    }
}
