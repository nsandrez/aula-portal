<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_pantalla_de_login_puede_ser_renderizada(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Aula Portal');
        $response->assertSee('Correo Institucional o RUT Chileno');
        $response->assertSee('Contraseña');
    }

    public function test_usuario_puede_iniciar_sesion_con_correo(): void
    {
        $user = User::factory()->create([
            'email' => 'estudiante@colegio.cl',
            'password' => Hash::make('secreto123'),
        ]);

        $response = $this->post('/login', [
            'identificador' => 'estudiante@colegio.cl',
            'password' => 'secreto123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    public function test_usuario_puede_iniciar_sesion_con_rut_formateado(): void
    {
        $user = User::factory()->create([
            'rut' => '12.345.678-5',
            'email' => 'docente@colegio.cl',
            'password' => Hash::make('claveDocente2026'),
        ]);

        $response = $this->post('/login', [
            'identificador' => '12.345.678-5',
            'password' => 'claveDocente2026',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    public function test_usuario_puede_iniciar_sesion_con_rut_limpio_cuando_esta_guardado_con_formato(): void
    {
        $user = User::factory()->create([
            'rut' => '12.345.678-5',
            'email' => 'apoderado@colegio.cl',
            'password' => Hash::make('claveApoderado1'),
        ]);

        $response = $this->post('/login', [
            'identificador' => '123456785',
            'password' => 'claveApoderado1',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    public function test_usuario_no_puede_iniciar_sesion_con_clave_incorrecta(): void
    {
        User::factory()->create([
            'email' => 'alumno@colegio.cl',
            'password' => Hash::make('claveCorrecta1'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'identificador' => 'alumno@colegio.cl',
            'password' => 'claveErronea',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('identificador');
    }

    public function test_validacion_falla_cuando_los_campos_estan_vacios(): void
    {
        $response = $this->from('/login')->post('/login', [
            'identificador' => '',
            'password' => '',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['identificador', 'password']);
    }

    public function test_usuario_autenticado_puede_cerrar_sesion(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
