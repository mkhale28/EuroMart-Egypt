<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_visitor_can_add_to_cart(): void
    {
        $product = Product::factory()->create(['status' => 'active', 'stock' => 10]);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('carts', ['product_id' => $product->id]);
    }

    public function test_cart_updates_quantity(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['status' => 'active', 'stock' => 10]);
        $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $cartItem = \App\Models\Cart::first();

        $response = $this->actingAs($user)->patch("/cart/{$cartItem->id}", ['quantity' => 3]);
        $response->assertRedirect();

        $this->assertEquals(3, $cartItem->fresh()->quantity);
    }

    public function test_cart_remove_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['status' => 'active', 'stock' => 10]);
        $this->actingAs($user)->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

        $cartItem = \App\Models\Cart::first();

        $response = $this->actingAs($user)->delete("/cart/{$cartItem->id}");
        $response->assertRedirect();
        $this->assertDatabaseMissing('carts', ['id' => $cartItem->id]);
    }

    public function test_authenticated_user_can_checkout(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['status' => 'active', 'stock' => 10, 'price' => 100]);

        $this->actingAs($user);
        $this->post('/cart/add', ['product_id' => $product->id, 'quantity' => 2]);

        $response = $this->actingAs($user)->post('/checkout/process', [
            'full_name' => 'Test Customer',
            'phone' => '01012345678',
            'governorate' => 'Cairo',
            'address' => '123 Test St',
            'payment_method' => 'cod',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['customer_phone' => '01012345678']);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id]);
    }

    public function test_out_of_stock_product_cannot_be_added_to_cart(): void
    {
        $product = Product::factory()->create(['status' => 'active', 'stock' => 0]);

        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
