<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cedula' => $this->faker->unique()->numberBetween(30000000, 40000000),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'birth_date' => $this->faker->date('Y-m-d', '-6 years'),
            'birth_place_state' => 'Yaracuy',
            'birth_place_locality' => 'San Felipe',
            'gender' => $this->faker->randomElement(['M', 'F']),
            'shirt_size' => $this->faker->randomElement(['6', '8', '10', '12']),
            'pants_size' => $this->faker->randomElement(['6', '8', '10', '12']),
            'shoes_size' => (string)$this->faker->numberBetween(28, 36),
            'weight' => $this->faker->randomFloat(2, 20, 50),
            'height' => $this->faker->randomFloat(2, 1, 1.6),
            'is_active' => true,
            'status' => 'Activo',
        ];
    }
}
