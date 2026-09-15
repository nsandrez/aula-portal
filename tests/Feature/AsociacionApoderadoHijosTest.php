<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RolUsuario;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsociacionApoderadoHijosTest extends TestCase
{
    use RefreshDatabase;

    public function test_administrador_puede_vincular_multiples_estudiantes_a_un_apoderado(): void
    {
        $admin = User::factory()->create(['rol' => RolUsuario::Administrador]);
        $apoderado = User::factory()->create(['rol' => RolUsuario::Apoderado, 'name' => 'Claudio Sánchez']);

        $curso = Curso::create([
            'nombre' => '1° Medio A',
            'nivel' => 'Enseñanza Media',
            'anio' => 2026,
        ]);

        $hijo1 = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Tomás Sánchez']);
        $hijo2 = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Valentina Sánchez']);

        Matricula::create([
            'estudiante_id' => $hijo1->id,
            'curso_id' => $curso->id,
            'numero_lista' => 1,
            'anio' => 2026,
            'estado' => 'regular',
        ]);

        Matricula::create([
            'estudiante_id' => $hijo2->id,
            'curso_id' => $curso->id,
            'numero_lista' => 2,
            'anio' => 2026,
            'estado' => 'regular',
        ]);

        $response = $this->actingAs($admin)->post('/matriculas/asociar-apoderado', [
            'apoderado_id' => $apoderado->id,
            'estudiante_ids' => [$hijo1->id, $hijo2->id],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('exito');

        $this->assertDatabaseHas('matriculas', [
            'estudiante_id' => $hijo1->id,
            'apoderado_id' => $apoderado->id,
        ]);

        $this->assertDatabaseHas('matriculas', [
            'estudiante_id' => $hijo2->id,
            'apoderado_id' => $apoderado->id,
        ]);

        $this->assertCount(2, $apoderado->fresh()->pupilosMatriculados);
    }

    public function test_apoderado_ve_a_sus_multiples_hijos_en_el_panel_principal(): void
    {
        $apoderado = User::factory()->create(['rol' => RolUsuario::Apoderado, 'name' => 'Marcela Contreras']);
        $curso = Curso::create(['nombre' => '1° Medio A', 'nivel' => 'Enseñanza Media', 'anio' => 2026]);

        $hijo1 = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Sofía Álvarez']);
        $hijo2 = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Matías Bustamante']);

        Matricula::create([
            'estudiante_id' => $hijo1->id,
            'curso_id' => $curso->id,
            'apoderado_id' => $apoderado->id,
            'numero_lista' => 1,
            'anio' => 2026,
            'estado' => 'regular',
        ]);

        Matricula::create([
            'estudiante_id' => $hijo2->id,
            'curso_id' => $curso->id,
            'apoderado_id' => $apoderado->id,
            'numero_lista' => 2,
            'anio' => 2026,
            'estado' => 'regular',
        ]);

        $response = $this->actingAs($apoderado)->get('/');

        $response->assertOk();
        $response->assertSee('Mis Pupilos / Hijos a Cargo (2)');
        $response->assertSee('Sofía Álvarez');
        $response->assertSee('Matías Bustamante');
        $response->assertSee('2 Hijos');
    }

    public function test_apoderado_puede_alternar_entre_pupilos_en_notas_y_asistencias(): void
    {
        $apoderado = User::factory()->create(['rol' => RolUsuario::Apoderado]);
        $curso = Curso::create(['nombre' => '1° Medio A', 'nivel' => 'Enseñanza Media', 'anio' => 2026]);

        $hijo1 = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Sofía Álvarez']);
        $hijo2 = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Matías Bustamante']);

        Matricula::create([
            'estudiante_id' => $hijo1->id,
            'curso_id' => $curso->id,
            'apoderado_id' => $apoderado->id,
            'numero_lista' => 1,
            'anio' => 2026,
            'estado' => 'regular',
        ]);

        Matricula::create([
            'estudiante_id' => $hijo2->id,
            'curso_id' => $curso->id,
            'apoderado_id' => $apoderado->id,
            'numero_lista' => 2,
            'anio' => 2026,
            'estado' => 'regular',
        ]);

        // Ver notas de hijo 2
        $respNotasHijo2 = $this->actingAs($apoderado)->get("/notas?pupilo_id={$hijo2->id}");
        $respNotasHijo2->assertOk();
        $respNotasHijo2->assertSee('Calificaciones del Primer Semestre - Matías Bustamante');

        // Ver asistencias de hijo 1
        $respAsisHijo1 = $this->actingAs($apoderado)->get("/asistencias?pupilo_id={$hijo1->id}");
        $respAsisHijo1->assertOk();
        $respAsisHijo1->assertSee('Historial Reciente Día a Día - Sofía Álvarez');
    }
}
