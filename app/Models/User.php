<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RolUsuario;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'rut', 'rol', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'rol' => RolUsuario::class,
        ];
    }

    /**
     * Verifica si el usuario posee el rol de SuperUsuario.
     */
    public function esSuperUsuario(): bool
    {
        return $this->rol === RolUsuario::SuperUsuario;
    }

    /**
     * Verifica si el usuario posee el rol de Administrador.
     */
    public function esAdministrador(): bool
    {
        return $this->rol === RolUsuario::Administrador;
    }

    /**
     * Verifica si el usuario posee el rol de Docente.
     */
    public function esDocente(): bool
    {
        return $this->rol === RolUsuario::Docente;
    }

    /**
     * Verifica si el usuario posee el rol de Estudiante.
     */
    public function esEstudiante(): bool
    {
        return $this->rol === RolUsuario::Estudiante;
    }

    /**
     * Verifica si el usuario posee el rol de Apoderado.
     */
    public function esApoderado(): bool
    {
        return $this->rol === RolUsuario::Apoderado;
    }

    /**
     * Comprueba si el usuario tiene cualquiera de los roles indicados.
     */
    public function tieneRol(RolUsuario ...$roles): bool
    {
        return in_array($this->rol, $roles, true);
    }
}
