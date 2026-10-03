<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['fixed', 'percentage']);

        return [
            'code' => strtoupper(fake()->unique()->bothify('???###')),
            'type' => $type,
            'value' => $type === 'fixed' ? fake()->randomFloat(2, 20, 500) : fake()->randomFloat(2, 5, 50),
            'min_order_amount' => fake()->boolean(50) ? fake()->randomFloat(2, 100, 1000) : null,
            'max_uses' => fake()->boolean(70) ? fake()->numberBetween(10, 1000) : null,
            'used_count' => fake()->numberBetween(0, 50),
            'max_discount' => $type === 'percentage' ? fake()->randomFloat(2, 100, 500) : null,
            'is_active' => true,
            'starts_at' => fake()->boolean(30) ? fake()->dateTimeBetween('-1 month', 'now') : null,
            'expires_at' => fake()->dateTimeBetween('+1 week', '+6 months'),
        ];
    }
}
