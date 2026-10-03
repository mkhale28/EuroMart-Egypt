<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $brands = ['L\'Oreal', 'Maybelline', 'Nivea', 'Dove', 'Garnier', 'Adidas', 'Nike', 'Puma', 'Zara', 'H&M', 'Gucci', 'Channel', 'Dior', 'Lancome', 'Estee Lauder'];
        $name = fake()->words(rand(2, 4), true);
        $price = fake()->randomFloat(2, 50, 5000);
        $hasSale = fake()->boolean(30);

        return [
            'category_id' => \App\Models\Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name . '-' . Str::random(4)),
            'description' => fake()->paragraphs(3, true),
            'short_description' => fake()->sentence(),
            'sku' => 'EM-' . strtoupper(Str::random(8)),
            'brand' => fake()->randomElement($brands),
            'price' => $price,
            'sale_price' => $hasSale ? $price * fake()->randomFloat(2, 0.5, 0.95) : null,
            'stock' => fake()->numberBetween(0, 200),
            'featured' => fake()->boolean(20),
            'status' => fake()->randomElement(['active', 'active', 'active', 'draft']),
            'meta_title' => ucfirst($name),
            'meta_description' => fake()->sentence(),
            'view_count' => fake()->numberBetween(0, 5000),
            'sold_count' => fake()->numberBetween(0, 200),
        ];
    }
}
