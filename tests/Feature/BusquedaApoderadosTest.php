<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RolUsuario;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusquedaApoderadosTest extends TestCase
{
    use RefreshDatabase;

    private User $administrador;

    private User $apoderadaMarcela;

    private User $apoderadoCarlos;

    protected function setUp(): void
    {
        parent::setUp();

        $this->administrador = User::factory()->create(['rol' => RolUsuario::Administrador]);

        $this->apoderadaMarcela = User::factory()->create([
            'rol' => RolUsuario::Apoderado,
            'name' => 'Marcela Contreras Silva',
            'rut' => '55.555.555-5',
            'email' => 'marcela@correo.cl',
        ]);

        $this->apoderadoCarlos = User::factory()->create([
            'rol' => RolUsuario::Apoderado,
            'name' => 'Carlos Andrés Pérez Gómez',
            'rut' => '12.345.678-9',
            'email' => 'carlos@correo.cl',
        ]);

        $curso = Curso::create(['nombre' => '1° Medio A', 'nivel' => 'Enseñanza Media', 'anio' => 2026]);
        $estudiante = User::factory()->create(['rol' => RolUsuario::Estudiante, 'name' => 'Daniela Castillo']);

        Matricula::create([
            'estudiante_id' => $estudiante->id,
            'curso_id' => $curso->id,
            'apoderado_id' => $this->apoderadaMarcela->id,
            'numero_lista' => 1,
            'anio' => 2026,
            'estado' => 'regular',
        ]);
    }

    public function test_busca_apoderado_por_rut_con_o_sin_formato(): void
    {
        foreach (['55555555', '555555555', '55.555.555-5', '55555555-5'] as $busqueda) {
            $this->actingAs($this->administrador)
                ->getJson(route('matriculas.buscar_apoderados', ['busqueda' => $busqueda]))
                ->assertOk()
                ->assertJsonCount(1, 'apoderados')
                ->assertJsonPath('apoderados.0.id', $this->apoderadaMarcela->id)
                ->assertJsonPath('apoderados.0.nombre', 'Marcela Contreras Silva')
                ->assertJsonPath('apoderados.0.rut', '55555555-5')
                ->assertJsonPath('apoderados.0.pupilos_count', 1)
                ->assertJsonPath('apoderados.0.pupilos.0', 'Daniela Castillo');
        }
    }

    public function test_busca_apoderado_por_nombre_y_primer_apellido(): void
    {
        $this->actingAs($this->administrador)
            ->getJson(route('matriculas.buscar_apoderados', ['busqueda' => 'Marcela Contreras']))
            ->assertOk()
            ->assertJsonCount(1, 'apoderados')
            ->assertJsonPath('apoderados.0.nombre', 'Marcela Contreras Silva');
    }

    public function test_busca_apoderado_por_nombre_y_segundo_apellido_dejando_el_primer_apellido_opcional(): void
    {
        // Marcela Contreras Silva se encuentra buscando "Marcela Silva"
        $this->actingAs($this->administrador)
            ->getJson(route('matriculas.buscar_apoderados', ['busqueda' => 'Marcela Silva']))
            ->assertOk()
            ->assertJsonCount(1, 'apoderados')
            ->assertJsonPath('apoderados.0.nombre', 'Marcela Contreras Silva');

        // Carlos Andrés Pérez Gómez se encuentra buscando "Carlos Gómez"
        $this->actingAs($this->administrador)
            ->getJson(route('matriculas.buscar_apoderados', ['busqueda' => 'Carlos Gómez']))
            ->assertOk()
            ->assertJsonCount(1, 'apoderados')
            ->assertJsonPath('apoderados.0.nombre', 'Carlos Andrés Pérez Gómez');
    }

    public function test_busca_apoderado_por_solo_primer_o_segundo_apellido(): void
    {
        // Primer apellido
        $this->actingAs($this->administrador)
            ->getJson(route('matriculas.buscar_apoderados', ['busqueda' => 'Contreras']))
            ->assertOk()
            ->assertJsonCount(1, 'apoderados')
            ->assertJsonPath('apoderados.0.nombre', 'Marcela Contreras Silva');

        // Segundo apellido
        $this->actingAs($this->administrador)
            ->getJson(route('matriculas.buscar_apoderados', ['busqueda' => 'Silva']))
            ->assertOk()
            ->assertJsonCount(1, 'apoderados')
            ->assertJsonPath('apoderados.0.nombre', 'Marcela Contreras Silva');
    }

    public function test_busca_apoderado_por_solo_primer_nombre(): void
    {
        $this->actingAs($this->administrador)
            ->getJson(route('matriculas.buscar_apoderados', ['busqueda' => 'Carlos']))
            ->assertOk()
            ->assertJsonCount(1, 'apoderados')
            ->assertJsonPath('apoderados.0.nombre', 'Carlos Andrés Pérez Gómez');
    }

    public function test_exige_al_menos_dos_caracteres_para_buscar(): void
    {
        $this->actingAs($this->administrador)
            ->getJson(route('matriculas.buscar_apoderados', ['busqueda' => 'M']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['busqueda' => 'Escribe al menos 2 caracteres para buscar.']);
    }

    public function test_docentes_estudiantes_y_apoderados_no_pueden_buscar_apoderados(): void
    {
        foreach ([RolUsuario::Docente, RolUsuario::Apoderado, RolUsuario::Estudiante] as $rol) {
            $this->actingAs(User::factory()->create(['rol' => $rol]))
                ->getJson(route('matriculas.buscar_apoderados', ['busqueda' => 'Marcela']))
                ->assertForbidden();
        }
    }

    public function test_modal_incluye_buscador_de_apoderados_en_la_vista(): void
    {
        $this->actingAs($this->administrador)
            ->get(route('matriculas.index'))
            ->assertOk()
            ->assertSee('1. Busca al apoderado')
            ->assertSee('data-campo-busqueda-apoderado', false)
            ->assertSee('data-resultados-busqueda-apoderado', false)
            ->assertSee('data-apoderado-seleccionado', false);
    }
}
