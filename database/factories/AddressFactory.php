<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'municipality' => 'San Felipe',
            'parish' => 'San Felipe',
            'sector' => $this->faker->streetName(),
            'house_apt_number' => $this->faker->buildingNumber(),
        ];
    }
}
