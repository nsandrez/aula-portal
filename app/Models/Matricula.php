<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas';

    protected $fillable = [
        'estudiante_id',
        'curso_id',
        'apoderado_id',
        'numero_lista',
        'anio',
        'estado',
    ];

    /**
     * Estudiante matriculado.
     */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    /**
     * Curso en el que se encuentra matriculado.
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    /**
     * Apoderado responsable del estudiante.
     */
    public function apoderado(): BelongsTo
    {
        return $this->belongsTo(User::class, 'apoderado_id');
    }
}
