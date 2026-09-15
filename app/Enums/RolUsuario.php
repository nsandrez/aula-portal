<?php

declare(strict_types=1);

namespace App\Enums;

enum RolUsuario: string
{
    case SuperUsuario = 'superusuario';
    case Administrador = 'administrador';
    case Docente = 'docente';
    case Estudiante = 'estudiante';
    case Apoderado = 'apoderado';

    /**
     * Retorna el nombre legible del rol escolar.
     */
    public function obtenerEtiqueta(): string
    {
        return match ($this) {
            self::SuperUsuario => 'Super Usuario TI',
            self::Administrador => 'Administrador',
            self::Docente => 'Docente',
            self::Estudiante => 'Estudiante',
            self::Apoderado => 'Apoderado',
        };
    }

    /**
     * Retorna clases de Tailwind para mostrar la insignia del rol con estilo escolar.
     */
    public function obtenerClasesInsignia(): string
    {
        return match ($this) {
            self::SuperUsuario => 'bg-purple-50 text-purple-700 border-purple-200',
            self::Administrador => 'bg-blue-50 text-blue-700 border-blue-200',
            self::Docente => 'bg-amber-50 text-amber-800 border-amber-200',
            self::Estudiante => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            self::Apoderado => 'bg-orange-50 text-orange-800 border-orange-200',
        };
    }
}
