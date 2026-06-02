<?php

namespace Database\Factories;

use App\Models\SchoolYear;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolYearFactory extends Factory
{
    protected $model = SchoolYear::class;

    public function definition(): array
    {
        $year = fake()->unique()->numberBetween(2015, 2030);
        return [
            'year' => "$year-" . ($year + 1),
            'is_active' => false,
        ];
    }
}
