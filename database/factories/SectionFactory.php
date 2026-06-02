<?php

namespace Database\Factories;

use App\Models\Section;
use App\Models\Grade;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectionFactory extends Factory
{
    protected $model = Section::class;

    public function definition(): array
    {
        return [
            'grade_id' => Grade::inRandomOrder()->first()?->id ?? 1,
            'name' => fake()->randomElement(['A', 'B', 'C', 'D']),
            'shift' => fake()->randomElement(['morning', 'afternoon']),
        ];
    }
}
