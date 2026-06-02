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
            'user_id' => User::factory()->create([
                'role_id' => Role::where('name', 'worker')->first()?->id ?? 2
            ])->id,
            'gender' => fake()->randomElement(['M', 'F']),
            'birth_date' => fake()->date('Y-m-d', '-25 years'),
            'entry_date' => fake()->date('Y-m-d', '-5 years'),
            'academic_degree' => fake()->randomElement(['Licenciado', 'Magister', 'Doctor']),
            'status' => 'Activo',
        ];
    }
}
