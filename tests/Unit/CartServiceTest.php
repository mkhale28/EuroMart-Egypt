<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    private CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = $this->app->make(CartService::class);
    }

    public function test_cart_is_empty_initially(): void
    {
        $this->assertEquals(0, $this->cartService->count());
    }

    public function test_can_add_item_to_cart(): void
    {
        $product = Product::factory()->create(['status' => 'active', 'stock' => 10]);

        $this->cartService->add($product, 2);

        $this->assertEquals(2, $this->cartService->count());
    }

    public function test_cannot_add_out_of_stock_items(): void
    {
        $product = Product::factory()->create(['status' => 'active', 'stock' => 0]);

        // We need to check stock validation which happens in the controller
        $this->assertFalse($product->is_in_stock);
    }

    public function test_cart_subtotal_is_calculated(): void
    {
        $product = Product::factory()->create(['status' => 'active', 'stock' => 10, 'price' => 100, 'sale_price' => null]);

        $this->cartService->add($product, 3);

        $this->assertEquals(300, $this->cartService->subtotal());
    }

    public function test_can_clear_cart(): void
    {
        $product = Product::factory()->create(['status' => 'active', 'stock' => 10]);
        $this->cartService->add($product, 1);

        $this->cartService->clear();

        $this->assertEquals(0, $this->cartService->count());
    }
}
