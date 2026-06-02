<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\Student;
use App\Models\Representative;
use App\Models\Enrollment;
use App\Models\Address;
use App\Models\Grade;
use App\Models\Subject;

class SystemTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ensure we have basic data from DatabaseSeeder
        $this->call(DatabaseSeeder::class);

        // 2. Create School Years
        $this->command->info('Creando años escolares...');
        $currentYear = SchoolYear::firstOrCreate(['year' => '2024-2025'], ['is_active' => true]);
        SchoolYear::firstOrCreate(['year' => '2023-2024'], ['is_active' => false]);
        
        // 3. Create Teachers
        $this->command->info('Creando docentes...');
        $teachers = Teacher::factory(10)->create();

        // 4. Create Subjects
        $this->command->info('Creando materias...');
        if (Subject::count() === 0) {
            $this->call(SubjectSeeder::class);
        }

        // 5. Create Sections for the current year
        $this->command->info('Creando secciones...');
        $grades = Grade::all();
        $sections = collect();
        
        foreach ($grades as $grade) {
            foreach (['A', 'B'] as $name) {
                $sections->push(Section::create([
                    'grade_id' => $grade->id,
                    'name' => $name,
                    'shift' => fake()->randomElement(['morning', 'afternoon']),
                ]));
            }
        }

        // 6. Create Students, Representatives and Enrollments
        $this->command->info('Creando estudiantes e inscripciones...');
        
        Student::factory(50)->create()->each(function ($student) use ($sections, $currentYear) {
            // Address
            Address::factory()->create(['student_id' => $student->id]);
            
            // Representative
            $representative = Representative::factory()->create();
            
            // Enrollment
            Enrollment::factory()->create([
                'student_id' => $student->id,
                'representative_id' => $representative->id,
                'section_id' => $sections->random()->id,
                'school_year' => $currentYear->year,
                'status' => 'Inscrito'
            ]);
        });

        $this->command->info('¡Datos de prueba generados exitosamente!');
    }
}
