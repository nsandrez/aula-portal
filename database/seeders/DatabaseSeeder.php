<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RolUsuario;
use App\Models\Asignatura;
use App\Models\Asistencia;
use App\Models\Curso;
use App\Models\CursoAsignatura;
use App\Models\Matricula;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $passwordPorDefecto = Hash::make('password123');

        // 1. Usuarios Principales
        $superAdmin = User::updateOrCreate(
            ['email' => 'super@aula-portal.cl'],
            [
                'name' => 'Nicolás Super Admin',
                'rut' => '11111111-1',
                'rol' => RolUsuario::SuperUsuario,
                'password' => $passwordPorDefecto,
            ]
        );

        $director = User::updateOrCreate(
            ['email' => 'admin@aula-portal.cl'],
            [
                'name' => 'Dirección Académica',
                'rut' => '22222222-2',
                'rol' => RolUsuario::Administrador,
                'password' => $passwordPorDefecto,
            ]
        );

        $docenteSanchez = User::updateOrCreate(
            ['email' => 'docente@aula-portal.cl'],
            [
                'name' => 'Prof. Rodrigo Sánchez',
                'rut' => '33333333-3',
                'rol' => RolUsuario::Docente,
                'password' => $passwordPorDefecto,
            ]
        );

        $docenteSilva = User::updateOrCreate(
            ['email' => 'profesora.silva@aula-portal.cl'],
            [
                'name' => 'Prof. Carolina Silva',
                'rut' => '15443322-1',
                'rol' => RolUsuario::Docente,
                'password' => $passwordPorDefecto,
            ]
        );

        $docenteGonzalez = User::updateOrCreate(
            ['email' => 'profesor.gonzalez@aula-portal.cl'],
            [
                'name' => 'Prof. Mauricio González',
                'rut' => '16778899-2',
                'rol' => RolUsuario::Docente,
                'password' => $passwordPorDefecto,
            ]
        );

        $apoderadaMarcela = User::updateOrCreate(
            ['email' => 'apoderado@aula-portal.cl'],
            [
                'name' => 'Marcela Contreras (Apoderada)',
                'rut' => '55555555-5',
                'rol' => RolUsuario::Apoderado,
                'password' => $passwordPorDefecto,
            ]
        );

        $estudianteSofia = User::updateOrCreate(
            ['email' => 'estudiante@aula-portal.cl'],
            [
                'name' => 'Sofía Álvarez Contreras',
                'rut' => '44444444-4',
                'rol' => RolUsuario::Estudiante,
                'password' => $passwordPorDefecto,
            ]
        );

        $estudianteMatias = User::updateOrCreate(
            ['email' => 'alumno.matias@aula-portal.cl'],
            [
                'name' => 'Matías Bustamante Morales',
                'rut' => '22134567-8',
                'rol' => RolUsuario::Estudiante,
                'password' => $passwordPorDefecto,
            ]
        );

        $estudianteDaniela = User::updateOrCreate(
            ['email' => 'alumna.daniela@aula-portal.cl'],
            [
                'name' => 'Daniela Castillo Reyes',
                'rut' => '21987654-3',
                'rol' => RolUsuario::Estudiante,
                'password' => $passwordPorDefecto,
            ]
        );

        $estudianteLucas = User::updateOrCreate(
            ['email' => 'alumno.lucas@aula-portal.cl'],
            [
                'name' => 'Lucas Díaz Herrera',
                'rut' => '22345678-9',
                'rol' => RolUsuario::Estudiante,
                'password' => $passwordPorDefecto,
            ]
        );

        // 2. Catálogo de Cursos
        $curso1A = Curso::updateOrCreate(
            ['nombre' => '1° Medio A', 'anio' => 2026],
            [
                'nivel' => 'Enseñanza Media',
                'profesor_jefe_id' => $docenteSanchez->id,
            ]
        );

        $curso1B = Curso::updateOrCreate(
            ['nombre' => '1° Medio B', 'anio' => 2026],
            [
                'nivel' => 'Enseñanza Media',
                'profesor_jefe_id' => $docenteSilva->id,
            ]
        );

        $curso2A = Curso::updateOrCreate(
            ['nombre' => '2° Medio A', 'anio' => 2026],
            [
                'nivel' => 'Enseñanza Media',
                'profesor_jefe_id' => $docenteGonzalez->id,
            ]
        );

        // 3. Catálogo de Asignaturas
        $asigMat = Asignatura::updateOrCreate(
            ['codigo' => 'MAT-101'],
            ['nombre' => 'Matemáticas', 'descripcion' => 'Álgebra, funciones y geometría euclidiana']
        );

        $asigLen = Asignatura::updateOrCreate(
            ['codigo' => 'LEN-101'],
            ['nombre' => 'Lengua y Literatura', 'descripcion' => 'Comprensión lectora, análisis crítico y redacción']
        );

        $asigHis = Asignatura::updateOrCreate(
            ['codigo' => 'HIS-101'],
            ['nombre' => 'Historia y Ciencias Sociales', 'descripcion' => 'Historia contemporánea y formación ciudadana']
        );

        $asigCie = Asignatura::updateOrCreate(
            ['codigo' => 'CIE-101'],
            ['nombre' => 'Ciencias Naturales: Biología', 'descripcion' => 'Ecosistemas, genética y biología celular']
        );

        $asigIng = Asignatura::updateOrCreate(
            ['codigo' => 'ING-101'],
            ['nombre' => 'Idioma Extranjero: Inglés', 'descripcion' => 'Competencias comunicativas orales y escritas']
        );

        // 4. Asociación de Asignaturas a Cada Curso con su Docente Asignado
        $asignaciones = [
            // 1° Medio A
            ['curso_id' => $curso1A->id, 'asignatura_id' => $asigMat->id, 'docente_id' => $docenteSanchez->id, 'horas' => 6],
            ['curso_id' => $curso1A->id, 'asignatura_id' => $asigLen->id, 'docente_id' => $docenteSilva->id, 'horas' => 6],
            ['curso_id' => $curso1A->id, 'asignatura_id' => $asigHis->id, 'docente_id' => $docenteGonzalez->id, 'horas' => 4],
            ['curso_id' => $curso1A->id, 'asignatura_id' => $asigCie->id, 'docente_id' => $docenteSanchez->id, 'horas' => 4],
            ['curso_id' => $curso1A->id, 'asignatura_id' => $asigIng->id, 'docente_id' => $docenteSilva->id, 'horas' => 4],

            // 1° Medio B
            ['curso_id' => $curso1B->id, 'asignatura_id' => $asigMat->id, 'docente_id' => $docenteSanchez->id, 'horas' => 6],
            ['curso_id' => $curso1B->id, 'asignatura_id' => $asigLen->id, 'docente_id' => $docenteSilva->id, 'horas' => 6],
            ['curso_id' => $curso1B->id, 'asignatura_id' => $asigHis->id, 'docente_id' => $docenteGonzalez->id, 'horas' => 4],

            // 2° Medio A
            ['curso_id' => $curso2A->id, 'asignatura_id' => $asigMat->id, 'docente_id' => $docenteSanchez->id, 'horas' => 6],
            ['curso_id' => $curso2A->id, 'asignatura_id' => $asigLen->id, 'docente_id' => $docenteSilva->id, 'horas' => 6],
            ['curso_id' => $curso2A->id, 'asignatura_id' => $asigHis->id, 'docente_id' => $docenteGonzalez->id, 'horas' => 4],
        ];

        foreach ($asignaciones as $item) {
            CursoAsignatura::updateOrCreate(
                [
                    'curso_id' => $item['curso_id'],
                    'asignatura_id' => $item['asignatura_id'],
                ],
                [
                    'docente_id' => $item['docente_id'],
                    'horas_semanales' => $item['horas'],
                ]
            );
        }

        // 5. Matrículas de Estudiantes en 1° Medio A
        $matriculas = [
            ['estudiante_id' => $estudianteSofia->id, 'apoderado_id' => $apoderadaMarcela->id, 'lista' => 1],
            ['estudiante_id' => $estudianteMatias->id, 'apoderado_id' => $apoderadaMarcela->id, 'lista' => 2],
            ['estudiante_id' => $estudianteDaniela->id, 'apoderado_id' => null, 'lista' => 3],
            ['estudiante_id' => $estudianteLucas->id, 'apoderado_id' => null, 'lista' => 4],
        ];

        foreach ($matriculas as $m) {
            Matricula::updateOrCreate(
                [
                    'estudiante_id' => $m['estudiante_id'],
                    'anio' => 2026,
                ],
                [
                    'curso_id' => $curso1A->id,
                    'apoderado_id' => $m['apoderado_id'],
                    'numero_lista' => $m['lista'],
                    'estado' => 'regular',
                ]
            );
        }

        // 6. Asistencia Diaria (Día a Día) de los últimos días escolares
        $fechas = ['2026-09-11', '2026-09-12', '2026-09-13', '2026-09-14', '2026-09-15'];

        foreach ($fechas as $fecha) {
            // Sofía siempre presente
            Asistencia::updateOrCreate(
                ['curso_id' => $curso1A->id, 'estudiante_id' => $estudianteSofia->id, 'fecha' => $fecha],
                ['estado' => 'presente', 'hora_llegada' => '07:55:00', 'observacion' => 'A tiempo', 'registrado_por_id' => $docenteSanchez->id]
            );

            // Matías: presente excepto el día 14 que tuvo atraso
            $estadoMatias = ($fecha === '2026-09-14') ? 'atraso' : 'presente';
            $horaMatias = ($fecha === '2026-09-14') ? '08:22:00' : '07:58:00';
            $obsMatias = ($fecha === '2026-09-14') ? 'Pase de inspectoría' : null;
            Asistencia::updateOrCreate(
                ['curso_id' => $curso1A->id, 'estudiante_id' => $estudianteMatias->id, 'fecha' => $fecha],
                ['estado' => $estadoMatias, 'hora_llegada' => $horaMatias, 'observacion' => $obsMatias, 'registrado_por_id' => $docenteSanchez->id]
            );

            // Daniela: ausente justificado el día 15
            $estadoDaniela = ($fecha === '2026-09-15') ? 'justificado' : 'presente';
            $obsDaniela = ($fecha === '2026-09-15') ? 'Licencia médica entregada' : null;
            Asistencia::updateOrCreate(
                ['curso_id' => $curso1A->id, 'estudiante_id' => $estudianteDaniela->id, 'fecha' => $fecha],
                ['estado' => $estadoDaniela, 'hora_llegada' => '07:50:00', 'observacion' => $obsDaniela, 'registrado_por_id' => $docenteSanchez->id]
            );

            // Lucas: ausente sin justificar el día 15
            $estadoLucas = ($fecha === '2026-09-15') ? 'ausente' : 'presente';
            $obsLucas = ($fecha === '2026-09-15') ? 'Sin aviso de apoderado' : null;
            Asistencia::updateOrCreate(
                ['curso_id' => $curso1A->id, 'estudiante_id' => $estudianteLucas->id, 'fecha' => $fecha],
                ['estado' => $estadoLucas, 'hora_llegada' => null, 'observacion' => $obsLucas, 'registrado_por_id' => $docenteSanchez->id]
            );
        }
    }
}
