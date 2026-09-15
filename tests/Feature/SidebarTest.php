<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RolUsuario;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarTest extends TestCase
{
    use RefreshDatabase;

    public function test_usuario_no_autenticado_es_redirigido_al_login_desde_el_panel(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/login');
    }

    public function test_usuario_no_autenticado_es_redirigido_al_login_desde_los_modulos(): void
    {
        $this->get('/notas')->assertRedirect('/login');
        $this->get('/asistencias')->assertRedirect('/login');
        $this->get('/cursos')->assertRedirect('/login');
        $this->get('/matriculas')->assertRedirect('/login');
        $this->get('/usuarios')->assertRedirect('/login');
    }

    public function test_superusuario_ve_todos_los_modulos_en_sidebar(): void
    {
        $superUsuario = User::factory()->create([
            'name' => 'Nicolás Super Admin',
            'rol' => RolUsuario::SuperUsuario,
        ]);

        $response = $this->actingAs($superUsuario)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Aula Portal');
        $response->assertSee('Gestión de Notas');
        $response->assertSee('Control de Asistencia');
        $response->assertSee('Cursos y Asignaturas');
        $response->assertSee('Matrículas y Estudiantes');
        $response->assertSee('Administración TI');
        $response->assertSee('Usuarios y Roles');
    }

    public function test_docente_ve_modulos_academicos_y_no_ve_administracion_ti(): void
    {
        $docente = User::factory()->create([
            'name' => 'Profesor Carlos',
            'rol' => RolUsuario::Docente,
        ]);

        $response = $this->actingAs($docente)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Gestión de Notas');
        $response->assertSee('Control de Asistencia');
        $response->assertDontSee('Administración TI');
        $response->assertDontSee('Usuarios y Roles');
    }

    public function test_estudiante_ve_sus_calificaciones_y_asistencias_personales(): void
    {
        $estudiante = User::factory()->create([
            'name' => 'Sofía Alumna',
            'rol' => RolUsuario::Estudiante,
        ]);

        $response = $this->actingAs($estudiante)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Mis Calificaciones');
        $response->assertSee('Mi Asistencia');
        $response->assertDontSee('Administración TI');
        $response->assertDontSee('Gestión de Notas');
    }

    public function test_apoderado_ve_opciones_de_pupilos(): void
    {
        $apoderado = User::factory()->create([
            'name' => 'Marcela Apoderada',
            'rol' => RolUsuario::Apoderado,
        ]);

        $response = $this->actingAs($apoderado)->get('/');

        $response->assertStatus(200);
        $response->assertSee('Boletín de Pupilos');
        $response->assertSee('Asistencia y Atrasos');
        $response->assertDontSee('Administración TI');
    }

    public function test_modulos_escolares_cargan_exitosamente_para_usuario_autenticado(): void
    {
        $usuario = User::factory()->create([
            'rol' => RolUsuario::Administrador,
        ]);

        $this->actingAs($usuario)->get('/notas')->assertStatus(200);
        $this->actingAs($usuario)->get('/asistencias')->assertStatus(200);
        $this->actingAs($usuario)->get('/cursos')->assertStatus(200);
        $this->actingAs($usuario)->get('/matriculas')->assertStatus(200);
        $this->actingAs($usuario)->get('/usuarios')->assertStatus(200);
    }
}
