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

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Aula Portal');
        $response->assertSee('Correo Institucional o RUT Chileno');
        $response->assertSee('Contraseña');
    }

    public function test_user_can_login_using_email(): void
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

    public function test_user_can_login_using_formatted_rut(): void
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

    public function test_user_can_login_using_clean_rut_when_stored_formatted(): void
    {
        $user = User::factory()->create([
            'rut' => '12.345.678-5',
            'email' => 'apoderado@colegio.cl',
            'password' => Hash::make('claveApoderado1'),
        ]);

        // Envía RUT sin puntos ni guión
        $response = $this->post('/login', [
            'identificador' => '123456785',
            'password' => 'claveApoderado1',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect('/');
    }

    public function test_user_cannot_login_with_incorrect_password(): void
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

    public function test_validation_fails_when_fields_are_empty(): void
    {
        $response = $this->from('/login')->post('/login', [
            'identificador' => '',
            'password' => '',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['identificador', 'password']);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
