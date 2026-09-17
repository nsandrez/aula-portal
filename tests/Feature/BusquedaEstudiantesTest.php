<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RolUsuario;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusquedaEstudiantesTest extends TestCase
{
    use RefreshDatabase;

    private User $administrador;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = User::factory()->create(['rol' => RolUsuario::Administrador]);
        $curso = Curso::create(['nombre' => '1° Medio A', 'nivel' => 'Enseñanza Media', 'anio' => 2026]);
        $apoderada = User::factory()->create(['rol' => RolUsuario::Apoderado, 'name' => 'Marcela Contreras']);

        $sofia = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Sofía Álvarez Contreras', 'rut' => '44.444.444-4']);
        $lucas = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Lucas Díaz Herrera', 'rut' => '22345678-9']);
        $sinMatricula = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Lucas Sin Matrícula', 'rut' => '11111111-1']);

        Matricula::create(['estudiante_id' => $sofia->id, 'curso_id' => $curso->id, 'apoderado_id' => $apoderada->id, 'numero_lista' => 1, 'anio' => 2026, 'estado' => 'regular']);
        Matricula::create(['estudiante_id' => $lucas->id, 'curso_id' => $curso->id, 'numero_lista' => 2, 'anio' => 2026, 'estado' => 'regular']);
        Matricula::create(['estudiante_id' => $sinMatricula->id, 'curso_id' => $curso->id, 'numero_lista' => 3, 'anio' => 2020, 'estado' => 'regular']);
    }

    public function test_busca_por_rut_con_o_sin_formato(): void
    {
        foreach (['44444444', '44.444.444-4', '444444444'] as $busqueda) {
            $this->actingAs($this->administrador)
                ->getJson(route('matriculas.buscar_estudiantes', ['busqueda' => $busqueda]))
                ->assertOk()
                ->assertJsonCount(1, 'estudiantes')
                ->assertJsonPath('estudiantes.0.nombre', 'Sofía Álvarez Contreras')
                ->assertJsonPath('estudiantes.0.rut', '44444444-4')
                ->assertJsonPath('estudiantes.0.apoderado_actual', 'Marcela Contreras');
        }
    }

    public function test_busca_por_nombre_solo_en_matriculas_del_anio_vigente(): void
    {
        $this->actingAs($this->administrador)
            ->getJson(route('matriculas.buscar_estudiantes', ['busqueda' => 'Lucas']))
            ->assertOk()
            ->assertJsonCount(1, 'estudiantes')
            ->assertJsonPath('estudiantes.0.nombre', 'Lucas Díaz Herrera');
    }

    public function test_exige_al_menos_tres_caracteres(): void
    {
        $this->actingAs($this->administrador)
            ->getJson(route('matriculas.buscar_estudiantes', ['busqueda' => 'Lu']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['busqueda' => 'Escribe al menos 3 caracteres para buscar.']);
    }

    public function test_docentes_y_apoderados_no_pueden_buscar_estudiantes(): void
    {
        foreach ([RolUsuario::Docente, RolUsuario::Apoderado, RolUsuario::Estudiante] as $rol) {
            $this->actingAs(User::factory()->create(['rol' => $rol]))
                ->getJson(route('matriculas.buscar_estudiantes', ['busqueda' => 'Lucas']))
                ->assertForbidden();
        }
    }

    public function test_modal_ya_no_lista_a_todos_los_estudiantes(): void
    {
        $this->actingAs($this->administrador)
            ->get(route('matriculas.index'))
            ->assertOk()
            ->assertSee('Busca a su hijo o pupilo')
            ->assertDontSee('estudiante_ids[]', false);
    }
}
