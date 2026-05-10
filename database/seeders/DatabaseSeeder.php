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
        $adminRole = \App\Models\Role::create(['name' => 'admin']);
        $workerRole = \App\Models\Role::create(['name' => 'worker']);
        $repRole = \App\Models\Role::create(['name' => 'representative']);

        // 2. Seed Educational Levels
        $inicial = \App\Models\EducationalLevel::create(['name' => 'Educación Inicial']);
        $primaria = \App\Models\EducationalLevel::create(['name' => 'Educación Primaria']);

        // 3. Seed Grades
        // Inicial: 1ero, 2do, 3ero
        foreach ([1, 2, 3] as $n) {
            \App\Models\Grade::create([
                'educational_level_id' => $inicial->id,
                'number' => $n,
                'name' => $n . 'er Nivel'
            ]);
        }

        // Primaria: 1ero a 6to
        foreach ([1, 2, 3, 4, 5, 6] as $n) {
             \App\Models\Grade::create([
                'educational_level_id' => $primaria->id,
                'number' => $n,
                'name' => $n . '° Grado'
            ]);
        }

        // 4. Seed Initial Admin User
        User::factory()->create([
            'first_name' => 'Administrador',
            'last_name'  => 'Páez',
            'email'      => 'admin@paez.edu',
            'cedula'     => 12345678,
            'role_id'    => $adminRole->id,
            'password'   => bcrypt('Admin123*'),
        ]);
        
        // 5. Seed Security Questions
        \App\Models\SecurityQuestion::create(['question' => '¿Cuál es el nombre de tu primera mascota?']);
        \App\Models\SecurityQuestion::create(['question' => '¿En qué ciudad naciste?']);
        \App\Models\SecurityQuestion::create(['question' => '¿Cuál es tu color favorito?']);
    }
}
