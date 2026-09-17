<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RolUsuario;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutorizacionMatriculaTest extends TestCase
{
    use RefreshDatabase;

    public function test_docente_no_puede_vincular_apoderados(): void
    {
        $docente = User::factory()->create(['rol' => RolUsuario::Docente]);
        $apoderado = User::factory()->create(['rol' => RolUsuario::Apoderado]);
        $estudiante = User::factory()->create(['rol' => RolUsuario::Estudiante]);

        $this->actingAs($docente)
            ->post(route('matriculas.asociar_apoderado'), [
                'apoderado_id' => $apoderado->id,
                'estudiante_ids' => [$estudiante->id],
            ])
            ->assertForbidden();
    }

    public function test_solo_superusuario_puede_actualizar_una_matricula(): void
    {
        $curso = Curso::create(['nombre' => '2° Básico A', 'nivel' => 'Enseñanza Básica', 'anio' => 2026]);
        $estudiante = User::factory()->create(['rol' => RolUsuario::Estudiante]);
        $matricula = Matricula::create([
            'estudiante_id' => $estudiante->id,
            'curso_id' => $curso->id,
            'numero_lista' => 3,
            'anio' => 2026,
            'estado' => 'regular',
        ]);
        $datos = ['numero_lista' => 7, 'estado' => 'retirado'];

        $administrador = User::factory()->create(['rol' => RolUsuario::Administrador]);
        $this->actingAs($administrador)
            ->put(route('matriculas.actualizar', $matricula), $datos)
            ->assertForbidden();

        $superUsuario = User::factory()->create(['rol' => RolUsuario::SuperUsuario]);
        $this->actingAs($superUsuario)
            ->put(route('matriculas.actualizar', $matricula), $datos)
            ->assertSessionHasNoErrors()
            ->assertSessionHas('exito');

        $this->assertDatabaseHas('matriculas', ['id' => $matricula->id, 'numero_lista' => 7, 'estado' => 'retirado']);
    }

    public function test_matricula_de_estudiante_nuevo_usa_la_clave_inicial(): void
    {
        $administrador = User::factory()->create(['rol' => RolUsuario::Administrador]);
        $curso = Curso::create(['nombre' => '3° Básico B', 'nivel' => 'Enseñanza Básica', 'anio' => 2026]);

        $this->actingAs($administrador)
            ->post(route('matriculas.guardar'), [
                'tipo_registro' => 'nuevo',
                'nombre_estudiante' => 'Ignacia Rojas',
                'email_estudiante' => 'ignacia.rojas@colegio.cl',
                'curso_id' => $curso->id,
                'numero_lista' => 4,
                'anio' => 2026,
            ])
            ->assertSessionHasNoErrors();

        $this->assertTrue(
            $this->app['hash']->check('estudiante2026', User::where('email', 'ignacia.rojas@colegio.cl')->value('password'))
        );
    }
}
