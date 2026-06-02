<?php

namespace Database\Factories;

use App\Models\Representative;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepresentativeFactory extends Factory
{
    protected $model = Representative::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create([
                'role_id' => Role::where('name', 'representative')->first()?->id ?? 3
            ])->id,
            'phone_whatsapp' => fake()->phoneNumber(),
            'relationship' => fake()->randomElement(['Madre', 'Padre', 'Abuela', 'Tío']),
        ];
    }
}
