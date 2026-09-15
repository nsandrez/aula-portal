<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CursoAsignatura extends Model
{
    use HasFactory;

    protected $table = 'curso_asignatura';

    protected $fillable = [
        'curso_id',
        'asignatura_id',
        'docente_id',
        'horas_semanales',
    ];

    /**
     * Curso al cual pertenece la asignatura.
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    /**
     * Asignatura curricular.
     */
    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(Asignatura::class, 'asignatura_id');
    }

    /**
     * Docente a cargo de dictar la asignatura en el curso.
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'docente_id');
    }
}
