<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Representative>
 */
class RepresentativeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cedula' => $this->faker->unique()->numberBetween(10000000, 25000000),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'phone_whatsapp' => $this->faker->phoneNumber(),
            'relationship' => $this->faker->randomElement(['Madre', 'Padre', 'Abuela', 'Tío']),
        ];
    }
}
