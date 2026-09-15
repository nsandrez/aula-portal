<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RolUsuario;
use App\Models\Asignatura;
use App\Models\Curso;
use App\Models\CursoAsignatura;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CrudFormulariosTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrador_puede_crear_un_nuevo_curso(): void
    {
        $admin = User::factory()->create(['rol' => RolUsuario::Administrador]);
        $docente = User::factory()->create(['rol' => RolUsuario::Docente]);

        $response = $this->actingAs($admin)->post('/cursos', [
            'nombre' => '3° Medio B',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
            'profesor_jefe_id' => $docente->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('exito');
        $this->assertDatabaseHas('cursos', [
            'nombre' => '3° Medio B',
            'profesor_jefe_id' => $docente->id,
        ]);
    }

    public function test_superusuario_puede_actualizar_un_curso_y_otros_roles_no(): void
    {
        $superAdmin = User::factory()->create(['rol' => RolUsuario::SuperUsuario]);
        $docente = User::factory()->create(['rol' => RolUsuario::Docente]);

        $curso = Curso::create([
            'nombre' => '4° Medio A',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
        ]);

        // Docente no puede editar
        $this->actingAs($docente)->put("/cursos/{$curso->id}", [
            'nombre' => '4° Medio Modificado',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
        ])->assertForbidden();

        // SuperUsuario sí puede editar
        $response = $this->actingAs($superAdmin)->put("/cursos/{$curso->id}", [
            'nombre' => '4° Medio Modificado',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('exito');
        $this->assertDatabaseHas('cursos', ['nombre' => '4° Medio Modificado']);
    }

    public function test_administrador_puede_asociar_asignatura_a_un_curso(): void
    {
        $admin = User::factory()->create(['rol' => RolUsuario::Administrador]);
        $docente = User::factory()->create(['rol' => RolUsuario::Docente]);
        $curso = Curso::create([
            'nombre' => '1° Medio C',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
        ]);
        $asignatura = Asignatura::create([
            'nombre' => 'Química',
            'codigo' => 'QUI-101',
        ]);

        $response = $this->actingAs($admin)->post("/cursos/{$curso->id}/asignaturas", [
            'asignatura_id' => $asignatura->id,
            'docente_id' => $docente->id,
            'horas_semanales' => 4,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('exito');
        $this->assertDatabaseHas('curso_asignatura', [
            'curso_id' => $curso->id,
            'asignatura_id' => $asignatura->id,
            'docente_id' => $docente->id,
            'horas_semanales' => 4,
        ]);
    }

    public function test_superusuario_puede_desasociar_asignatura_de_un_curso(): void
    {
        $superAdmin = User::factory()->create(['rol' => RolUsuario::SuperUsuario]);
        $curso = Curso::create([
            'nombre' => '1° Medio D',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
        ]);
        $asignatura = Asignatura::create([
            'nombre' => 'Física',
            'codigo' => 'FIS-101',
        ]);

        $asociacion = CursoAsignatura::create([
            'curso_id' => $curso->id,
            'asignatura_id' => $asignatura->id,
            'horas_semanales' => 3,
        ]);

        $response = $this->actingAs($superAdmin)
            ->delete("/cursos/{$curso->id}/asignaturas/{$asociacion->id}");

        $response->assertRedirect();
        $response->assertSessionHas('exito');
        $this->assertDatabaseMissing('curso_asignatura', ['id' => $asociacion->id]);
    }

    public function test_docente_puede_guardar_asistencia_diaria_desde_el_formulario(): void
    {
        $docente = User::factory()->create(['rol' => RolUsuario::Docente]);
        $estudiante = User::factory()->create(['rol' => RolUsuario::Estudiante]);
        $curso = Curso::create([
            'nombre' => '1° Medio A',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
        ]);

        $response = $this->actingAs($docente)->post('/asistencias', [
            'curso_id' => $curso->id,
            'fecha' => '2026-09-16',
            'asistencias' => [
                [
                    'estudiante_id' => $estudiante->id,
                    'estado' => 'atraso',
                    'hora_llegada' => '08:15',
                    'observacion' => 'Atraso con pase',
                ],
            ],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('exito');
        $this->assertDatabaseHas('asistencias', [
            'curso_id' => $curso->id,
            'estudiante_id' => $estudiante->id,
            'fecha' => '2026-09-16',
            'estado' => 'atraso',
            'hora_llegada' => '08:15',
        ]);
    }

    public function test_superusuario_puede_crear_y_editar_usuarios(): void
    {
        $superAdmin = User::factory()->create(['rol' => RolUsuario::SuperUsuario]);

        // Crear usuario
        $responseCrear = $this->actingAs($superAdmin)->post('/usuarios', [
            'name' => 'Profesor Nuevo',
            'email' => 'profesor.nuevo@aula-portal.cl',
            'rut' => '18999888-7',
            'rol' => RolUsuario::Docente->value,
            'password' => 'claveSegura123',
        ]);

        $responseCrear->assertRedirect();
        $responseCrear->assertSessionHas('exito');

        $usuarioCreado = User::where('email', 'profesor.nuevo@aula-portal.cl')->firstOrFail();
        $this->assertEquals('Profesor Nuevo', $usuarioCreado->name);

        // Editar usuario
        $responseEditar = $this->actingAs($superAdmin)->put("/usuarios/{$usuarioCreado->id}", [
            'name' => 'Profesor Renombrado',
            'email' => 'profesor.nuevo@aula-portal.cl',
            'rut' => '18999888-7',
            'rol' => RolUsuario::Administrador->value,
        ]);

        $responseEditar->assertRedirect();
        $responseEditar->assertSessionHas('exito');
        $this->assertEquals('Profesor Renombrado', $usuarioCreado->fresh()->name);
        $this->assertEquals(RolUsuario::Administrador, $usuarioCreado->fresh()->rol);
    }
}
