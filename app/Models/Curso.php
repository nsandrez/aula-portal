<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'cursos';

    protected $fillable = [
        'nombre',
        'nivel',
        'anio',
        'profesor_jefe_id',
    ];

    /**
     * Obtiene el profesor jefe asignado al curso.
     */
    public function profesorJefe(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profesor_jefe_id');
    }

    /**
     * Asignaturas asociadas a este curso.
     */
    public function asignaturas(): BelongsToMany
    {
        return $this->belongsToMany(Asignatura::class, 'curso_asignatura', 'curso_id', 'asignatura_id')
            ->withPivot('id', 'docente_id', 'horas_semanales')
            ->withTimestamps();
    }

    /**
     * Relaciones explícitas de asignaturas con sus docentes.
     */
    public function cursoAsignaturas(): HasMany
    {
        return $this->hasMany(CursoAsignatura::class, 'curso_id');
    }

    /**
     * Matrículas de estudiantes pertenecientes a este curso.
     */
    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class, 'curso_id');
    }

    /**
     * Registros diarios de asistencia de este curso.
     */
    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class, 'curso_id');
    }
}
