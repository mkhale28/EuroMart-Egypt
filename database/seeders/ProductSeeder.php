<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Cosmetics', 'products' => [
                ['name' => 'L\'Oreal Paris Foundation', 'price' => 450, 'brand' => 'L\'Oreal', 'stock' => 50],
                ['name' => 'Maybelline Lipstick Set', 'price' => 320, 'brand' => 'Maybelline', 'stock' => 75],
                ['name' => 'Nivea Moisturizing Cream', 'price' => 280, 'brand' => 'Nivea', 'stock' => 100],
                ['name' => 'Garnier Micellar Water', 'price' => 195, 'brand' => 'Garnier', 'stock' => 60],
                ['name' => 'Dove Body Wash 500ml', 'price' => 165, 'brand' => 'Dove', 'stock' => 90],
                ['name' => 'Channel No. 5 Perfume', 'price' => 4500, 'brand' => 'Channel', 'stock' => 15],
            ]],
            ['name' => 'Shoes', 'products' => [
                ['name' => 'Adidas Ultraboost Running', 'price' => 3200, 'brand' => 'Adidas', 'stock' => 25],
                ['name' => 'Nike Air Max 270', 'price' => 3800, 'brand' => 'Nike', 'stock' => 20],
                ['name' => 'Puma Suede Classic', 'price' => 1800, 'brand' => 'Puma', 'stock' => 35],
                ['name' => 'Zara Leather Loafers', 'price' => 2200, 'brand' => 'Zara', 'stock' => 30],
                ['name' => 'H&M Canvas Sneakers', 'price' => 950, 'brand' => 'H&M', 'stock' => 45],
            ]],
            ['name' => 'Bags', 'products' => [
                ['name' => 'Zara Tote Bag', 'price' => 1800, 'brand' => 'Zara', 'stock' => 20],
                ['name' => 'H&M Crossbody Bag', 'price' => 1200, 'brand' => 'H&M', 'stock' => 35],
                ['name' => 'Gucci Mini Backpack', 'price' => 8500, 'brand' => 'Gucci', 'stock' => 5],
                ['name' => 'Adidas Gym Sack', 'price' => 650, 'brand' => 'Adidas', 'stock' => 50],
            ]],
            ['name' => 'Accessories', 'products' => [
                ['name' => 'Dior Sunglasses', 'price' => 3200, 'brand' => 'Dior', 'stock' => 15],
                ['name' => 'Fossil Gen 6 Watch', 'price' => 4500, 'brand' => 'Fossil', 'stock' => 10],
                ['name' => 'Swatch Classic Watch', 'price' => 1800, 'brand' => 'Swatch', 'stock' => 25],
                ['name' => 'Pandora Bracelet', 'price' => 2800, 'brand' => 'Pandora', 'stock' => 20],
            ]],
            ['name' => 'Home Products', 'products' => [
                ['name' => 'Tefal Non-Stick Pan Set', 'price' => 2500, 'brand' => 'Tefal', 'stock' => 20],
                ['name' => 'Zara Home Candle Set', 'price' => 650, 'brand' => 'Zara Home', 'stock' => 40],
                ['name' => 'Philips Air Fryer', 'price' => 4200, 'brand' => 'Philips', 'stock' => 12],
                ['name' => 'IKEA Bedding Set', 'price' => 1800, 'brand' => 'IKEA', 'stock' => 30],
            ]],
        ];

        foreach ($categories as $catData) {
            $category = Category::create([
                'name' => $catData['name'],
                'slug' => \Illuminate\Support\Str::slug($catData['name']),
                'description' => "Premium {$catData['name']} imported from Europe",
                'is_active' => true,
                'sort_order' => Category::count() + 1,
            ]);

            foreach ($catData['products'] as $prodData) {
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $prodData['name'],
                    'slug' => \Illuminate\Support\Str::slug($prodData['name']),
                    'description' => "High-quality {$prodData['name']} imported from Europe. 100% authentic product.",
                    'short_description' => "Premium {$prodData['brand']} product at the best price in Egypt.",
                    'sku' => 'EM-' . strtoupper(\Illuminate\Support\Str::random(8)),
                    'brand' => $prodData['brand'],
                    'price' => $prodData['price'],
                    'sale_price' => rand(0, 1) ? $prodData['price'] * 0.85 : null,
                    'stock' => $prodData['stock'],
                    'featured' => rand(0, 1),
                    'status' => 'active',
                ]);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'placeholder.jpg',
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);

                if (rand(0, 1)) {
                    Review::create([
                        'product_id' => $product->id,
                        'customer_name' => 'Verified Buyer',
                        'rating' => rand(4, 5),
                        'comment' => 'Excellent product! Fast shipping and authentic European quality.',
                        'is_approved' => true,
                    ]);
                }
            }
        }
    }
}
