<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subject;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $curriculum = [
            'Educación Estética' => [4], // 1er Grado
            'Educación Física' => [4, 5, 6, 7, 8, 9],
            'Inglés' => [4, 5, 6, 7, 8, 9],
            'Matemática' => [4, 5, 6, 7, 8, 9], 
            'Lengua y Literatura' => [4, 5, 6, 7, 8, 9],
            'Ciencias de la Naturaleza y Tecnología' => [5, 6, 7, 8, 9], 
            'Ciencias Sociales' => [5, 6, 7, 8, 9],
            'Ciudadanía e Identidad' => [5, 6, 7, 8, 9],
        ];

        foreach ($curriculum as $name => $gradeIds) {
            $subject = Subject::updateOrCreate(['name' => $name], [
                'description' => "Área académica de $name.",
                'is_active' => true,
            ]);
            $subject->grades()->sync($gradeIds);
        }
    }
}
