<?php

namespace Database\Seeders;

use App\Enums\RolUsuario;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $usuariosDemo = [
            [
                'name' => 'Nicolás Super Admin',
                'email' => 'super@aula-portal.cl',
                'rut' => '11111111-1',
                'rol' => RolUsuario::SuperUsuario,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Dirección Académica',
                'email' => 'admin@aula-portal.cl',
                'rut' => '22222222-2',
                'rol' => RolUsuario::Administrador,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Prof. Rodrigo Sánchez',
                'email' => 'docente@aula-portal.cl',
                'rut' => '33333333-3',
                'rol' => RolUsuario::Docente,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Sofía Álvarez (Alumna)',
                'email' => 'estudiante@aula-portal.cl',
                'rut' => '44444444-4',
                'rol' => RolUsuario::Estudiante,
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Marcela Contreras (Apoderada)',
                'email' => 'apoderado@aula-portal.cl',
                'rut' => '55555555-5',
                'rol' => RolUsuario::Apoderado,
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($usuariosDemo as $datosUsuario) {
            User::updateOrCreate(
                ['email' => $datosUsuario['email']],
                $datosUsuario
            );
        }
    }
}
