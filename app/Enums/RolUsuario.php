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
            self::SuperUsuario => 'bg-purple-950/60 text-purple-300 border-purple-800/40',
            self::Administrador => 'bg-blue-950/60 text-blue-300 border-blue-800/40',
            self::Docente => 'bg-amber-950/60 text-amber-300 border-amber-800/40',
            self::Estudiante => 'bg-emerald-950/60 text-emerald-300 border-emerald-800/40',
            self::Apoderado => 'bg-orange-950/60 text-orange-300 border-orange-800/40',
        };
    }
}
