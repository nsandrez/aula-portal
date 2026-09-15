<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asignatura extends Model
{
    use HasFactory;

    protected $table = 'asignaturas';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
    ];

    /**
     * Cursos donde se imparte esta asignatura.
     */
    public function cursos(): BelongsToMany
    {
        return $this->belongsToMany(Curso::class, 'curso_asignatura', 'asignatura_id', 'curso_id')
            ->withPivot('id', 'docente_id', 'horas_semanales')
            ->withTimestamps();
    }

    /**
     * Instancias de la relación intermedia curso-asignatura.
     */
    public function cursoAsignaturas(): HasMany
    {
        return $this->hasMany(CursoAsignatura::class, 'asignatura_id');
    }
}
