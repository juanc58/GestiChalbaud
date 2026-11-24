<?php

namespace Database\Factories;

use App\Models\Representative;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepresentativeFactory extends Factory
{
    protected $model = Representative::class;

    public function definition(): array
    {
        return [
            'cedula'            => fake()->unique()->numberBetween(5000000, 32000000),
            'first_name'        => fake()->firstName(),
            'second_name'       => fake()->optional(0.5)->firstName(),
            'last_name'         => fake()->lastName(),
            'second_last_name'  => fake()->optional(0.5)->lastName(),
            'email'             => fake()->optional(0.6)->safeEmail(),
            'phone_whatsapp'    => fake()->numerify('0414#######'),
            'phone_local'       => fake()->optional(0.4)->numerify('0212#######'),
            'job_title'         => fake()->optional(0.5)->jobTitle(),
            'workplace_address' => fake()->optional(0.4)->address(),
            'relationship'      => fake()->randomElement(['Madre', 'Padre', 'Abuela', 'Tío', 'Representante Legal']),
        ];
    }
}
