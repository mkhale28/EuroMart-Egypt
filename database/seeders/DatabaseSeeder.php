<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            ProductSeeder::class,
        ]);

        // Create testimonials
        $testimonials = [
            ['customer_name' => 'Sarah Ahmed', 'customer_title' => 'Loyal Customer', 'content' => 'Absolutely love shopping at EuroMart! The European products are authentic and the prices are very reasonable. Fast delivery too!', 'rating' => 5, 'sort_order' => 1],
            ['customer_name' => 'Mohamed Ali', 'customer_title' => 'Regular Buyer', 'content' => 'Best place to find imported European products in Egypt. High quality items and excellent customer service.', 'rating' => 5, 'sort_order' => 2],
            ['customer_name' => 'Nour Hassan', 'customer_title' => 'Verified Buyer', 'content' => 'I ordered cosmetics and they arrived within 2 days. The packaging was perfect and products are genuine.', 'rating' => 5, 'sort_order' => 3],
            ['customer_name' => 'Karim Youssef', 'customer_title' => 'Happy Customer', 'content' => 'Great selection of imported goods. The WhatsApp ordering is super convenient. Highly recommended!', 'rating' => 4, 'sort_order' => 4],
            ['customer_name' => 'Dina Mostafa', 'customer_title' => 'Fashion Lover', 'content' => 'Found exactly what I was looking for. The European shoes collection is amazing. Will definitely shop again!', 'rating' => 5, 'sort_order' => 5],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }

        // Create customer users
        User::factory(20)->create([
            'email_verified_at' => now(),
        ]);

        // Create coupons
        Coupon::factory(5)->create();
    }
}
