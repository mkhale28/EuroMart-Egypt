<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_submit_review(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['status' => 'active']);

        $response = $this->actingAs($user)
            ->post("/reviews/{$product->slug}", [
                'rating' => 5,
                'comment' => 'Great product!',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', ['product_id' => $product->id, 'rating' => 5]);
    }

    public function test_review_requires_rating(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['status' => 'active']);

        $response = $this->actingAs($user)
            ->post("/reviews/{$product->slug}", ['comment' => 'No rating']);

        $response->assertSessionHasErrors('rating');
    }

    public function test_admin_can_approve_review(): void
    {
        $admin = User::factory()->admin()->create();
        $review = Review::factory()->create(['is_approved' => false]);

        $response = $this->actingAs($admin)
            ->post("/admin/reviews/{$review->id}/approve");

        $response->assertRedirect();
        $this->assertTrue($review->fresh()->is_approved);
    }

    public function test_unapproved_reviews_not_shown(): void
    {
        $product = Product::factory()->create(['status' => 'active']);
        Review::factory()->create(['product_id' => $product->id, 'is_approved' => false]);
        Review::factory()->create(['product_id' => $product->id, 'is_approved' => true]);

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(200);
    }
}
