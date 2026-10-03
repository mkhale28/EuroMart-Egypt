<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => fake()->name(),
            'phone' => '01' . fake()->numerify('#########'),
            'governorate' => fake()->randomElement([
                'Cairo', 'Alexandria', 'Giza', 'Shubra El-Kheima',
                'Port Said', 'Suez', 'Luxor', 'Mansoura',
                'Tanta', 'Asyut', 'Ismailia', 'Faiyum',
                'Zagazig', 'Damietta', 'Aswan', 'Minya',
            ]),
            'city' => fake()->city(),
            'address' => fake()->address(),
            'landmark' => fake()->boolean(50) ? fake()->words(2, true) : null,
            'building' => (string) fake()->numberBetween(1, 200),
            'apartment' => (string) fake()->numberBetween(1, 50),
            'is_default' => false,
            'type' => fake()->randomElement(['home', 'work', 'other']),
        ];
    }

    public function default(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_default' => true,
        ]);
    }
}
