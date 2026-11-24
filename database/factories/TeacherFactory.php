<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'user_id' => fn () => User::factory()->createQuietly([
                'role_id'   => Role::firstOrCreate(['name' => 'docente'])->id,
                'is_active' => true,
            ])->id,
            'gender'          => fake()->randomElement(['M', 'F']),
            'birth_date'      => fake()->date('Y-m-d', '-25 years'),
            'entry_date'      => fake()->date('Y-m-d', '-5 years'),
            'academic_degree' => fake()->randomElement(['Licenciado en Educación', 'Magister', 'Profesor Integral']),
            'status'          => 'Activo',
        ];
    }
}
