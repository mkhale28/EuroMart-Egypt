<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 10000);
        $discount = fake()->boolean(20) ? fake()->randomFloat(2, 10, $subtotal * 0.3) : 0;
        $shipping = fake()->randomElement([0, 30, 50, 100]);

        return [
            'order_number' => 'EM-' . now()->format('Ymd') . '-' . str_pad(fake()->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'user_id' => User::factory(),
            'customer_name' => fake()->name(),
            'customer_phone' => '01' . fake()->numerify('#########'),
            'customer_email' => fake()->email(),
            'governorate' => fake()->randomElement(['Cairo', 'Alexandria', 'Giza', 'Port Said', 'Suez', 'Luxor', 'Mansoura', 'Tanta']),
            'address' => fake()->address(),
            'notes' => fake()->boolean(30) ? fake()->sentence() : null,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => $subtotal - $discount + $shipping,
            'payment_method' => fake()->randomElement(['cod', 'vodafone_cash', 'instapay']),
            'payment_status' => 'pending',
            'status' => fake()->randomElement(['pending', 'confirmed', 'processing', 'shipped', 'delivered']),
        ];
    }
}
