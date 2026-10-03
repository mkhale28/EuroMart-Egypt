<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 5);
        $price = fake()->randomFloat(2, 50, 3000);

        return [
            'product_id' => Product::factory(),
            'product_name' => fake()->words(3, true),
            'product_sku' => 'EM-' . strtoupper(fake()->bothify('???###')),
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => $price * $quantity,
        ];
    }
}
