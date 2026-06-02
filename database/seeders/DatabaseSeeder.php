<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Roles
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin']);
        $workerRole = \App\Models\Role::firstOrCreate(['name' => 'worker']);
        $repRole = \App\Models\Role::firstOrCreate(['name' => 'representative']);

        // 2. Seed Educational Levels
        $inicial = \App\Models\EducationalLevel::firstOrCreate(['name' => 'Educación Inicial']);
        $primaria = \App\Models\EducationalLevel::firstOrCreate(['name' => 'Educación Primaria']);

        // 3. Seed Grades
        // Inicial: 1ero, 2do, 3ero
        foreach ([1, 2, 3] as $n) {
            \App\Models\Grade::firstOrCreate([
                'educational_level_id' => $inicial->id,
                'number' => $n,
            ], [
                'name' => $n . 'er Nivel'
            ]);
        }

        // Primaria: 1ero a 6to
        foreach ([1, 2, 3, 4, 5, 6] as $n) {
             \App\Models\Grade::firstOrCreate([
                'educational_level_id' => $primaria->id,
                'number' => $n,
            ], [
                'name' => $n . '° Grado'
            ]);
        }

        // 4. Seed Initial Admin User
        \App\Models\User::updateOrCreate(
            ['cedula' => 12345678],
            [
                'first_name' => 'Administrador',
                'last_name'  => 'Páez',
                'email'      => 'admin@paez.edu',
                'role_id'    => $adminRole->id,
                'password'   => bcrypt('Admin123*'),
                'is_active'  => true,
            ]
        );
        
        // 5. Seed Security Questions
        $questions = [
            '¿Cuál es el nombre de tu primera mascota?',
            '¿En qué ciudad naciste?',
            '¿Cuál es tu color favorito?',
            '¿Cuál es el nombre de tu madre?',
            '¿Cuál era el nombre de tu primera escuela?',
            '¿Cuál es tu comida favorita?',
            '¿Cuál es el nombre de tu mejor amigo de la infancia?',
            '¿Cuál es el modelo de tu primer auto?',
            '¿En qué año te graduaste de la secundaria?',
            '¿Cuál es el nombre de tu autor favorito?',
            '¿Cuál es tu película favorita?',
            '¿Cuál es el nombre de tu abuelo materno?'
        ];

        foreach ($questions as $q) {
            \App\Models\SecurityQuestion::firstOrCreate(['question' => $q]);
        }
    }
}
