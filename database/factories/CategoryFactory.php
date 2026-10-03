<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Cosmetics', 'Skin Care', 'Hair Care', 'Perfumes',
            'Men\'s Shoes', 'Women\'s Shoes', 'Sports Shoes',
            'Handbags', 'Backpacks', 'Wallets',
            'Watches', 'Sunglasses', 'Jewelry',
            'Home Decor', 'Kitchenware', 'Bedding',
            'Imported Foods', 'Baby Products', 'Electronics',
            'Fashion Accessories',
        ]);

        $slug = Str::slug($name) . '-' . Str::random(4);

        return [
            'name' => $name,
            'slug' => $slug,
            'description' => fake()->paragraph(),
            'is_active' => true,
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}
