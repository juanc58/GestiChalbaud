<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\Representative;
use App\Models\Section;
use App\Models\Enrollment;
use App\Models\Graduate;
use App\Models\Address;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sections = Section::all();
        if ($sections->isEmpty()) {
            $this->command->error('No hay secciones creadas. Por favor crea secciones antes de correr el seeder.');
            return;
        }

        $this->command->info('Generando 40 estudiantes activos...');
        
        // Create 40 regular students
        Student::factory(40)->create()->each(function ($student) use ($sections) {
            Address::factory()->create(['student_id' => $student->id]);
            
            $representative = Representative::factory()->create();
            
            Enrollment::factory()->create([
                'student_id' => $student->id,
                'representative_id' => $representative->id,
                'section_id' => $sections->random()->id,
            ]);
        });

        $this->command->info('Generando 35 estudiantes egresados...');

        // Create 35 graduates to test pagination in graduates list
        Student::factory(35)->create(['status' => 'Egresado', 'is_active' => false])->each(function ($student) {
            Address::factory()->create(['student_id' => $student->id]);
            
            Graduate::factory()->create([
                'student_id' => $student->id,
                'promotion_year' => '2024-2025'
            ]);
        });

        $this->command->info('Datos de prueba generados correctamente.');
    }
}
