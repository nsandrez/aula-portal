<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RolUsuario;
use App\Utils\PeriodoEscolar;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
     * Normaliza un nombre a mayúsculas y sin tildes/acentos ortográficos.
     */
    public static function normalizarNombre(string $nombre): string
    {
        $sinTildes = strtr($nombre, [
            'á' => 'A', 'é' => 'E', 'í' => 'I', 'ó' => 'O', 'ú' => 'U',
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U',
            'ü' => 'U', 'Ü' => 'U',
            'à' => 'A', 'è' => 'E', 'ì' => 'I', 'ò' => 'O', 'ù' => 'U',
            'À' => 'A', 'È' => 'E', 'Ì' => 'I', 'Ò' => 'O', 'Ù' => 'U',
            'ä' => 'A', 'ë' => 'E', 'ï' => 'I', 'ö' => 'O',
            'Ä' => 'A', 'Ë' => 'E', 'Ï' => 'I', 'Ö' => 'O',
            'â' => 'A', 'ê' => 'E', 'î' => 'I', 'ô' => 'O', 'û' => 'U',
            'Â' => 'A', 'Ê' => 'E', 'Î' => 'I', 'Ô' => 'O', 'Û' => 'U',
        ]);

        return mb_strtoupper(trim((string) preg_replace('/\s+/', ' ', $sinTildes)));
    }

    /**
     * Guarda el nombre siempre en mayúsculas y sin tildes.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value !== null ? self::normalizarNombre($value) : null,
        );
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

    /**
     * Cursos donde el usuario es profesor jefe.
     */
    public function cursosComoProfesorJefe(): HasMany
    {
        return $this->hasMany(Curso::class, 'profesor_jefe_id');
    }

    /**
     * Asignaturas que dicta el docente en diversos cursos.
     */
    public function asignaturasDictadas(): HasMany
    {
        return $this->hasMany(CursoAsignatura::class, 'docente_id');
    }

    /**
     * Matrícula activa del estudiante para el año vigente.
     */
    public function matriculaActual(): HasOne
    {
        return $this->hasOne(Matricula::class, 'estudiante_id')->where('anio', PeriodoEscolar::anioVigente());
    }

    /**
     * Historial de matrículas del estudiante.
     */
    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'estudiante_id');
    }

    /**
     * Pupilos asignados al apoderado.
     */
    public function pupilosMatriculados(): HasMany
    {
        return $this->hasMany(Matricula::class, 'apoderado_id');
    }

    /**
     * Registros de asistencia del estudiante.
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'estudiante_id');
    }
}
