<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'curso_id',
        'estudiante_id',
        'fecha',
        'estado',
        'hora_llegada',
        'observacion',
        'registrado_por_id',
    ];

    /**
     * Casts de atributos.
     */
    protected function casts(): array
    {
        return [
            'fecha' => 'date:Y-m-d',
        ];
    }

    /**
     * Curso al que corresponde la asistencia.
     */
    public function curso(): BelongsTo
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    /**
     * Estudiante evaluado en la lista diaria.
     */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    /**
     * Funcionario o docente que registró la asistencia.
     */
    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por_id');
    }
}
