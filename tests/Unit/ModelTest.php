<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $customer = User::factory()->create();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($customer->isAdmin());
    }

    public function test_user_is_customer(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $this->assertTrue($customer->isCustomer());
    }

    public function test_product_has_discount(): void
    {
        $product = Product::factory()->create(['price' => 100, 'sale_price' => 80]);

        $this->assertTrue($product->has_discount);
        $this->assertEquals(20, $product->discount_percentage);
    }

    public function test_product_no_discount(): void
    {
        $product = Product::factory()->create(['price' => 100, 'sale_price' => null]);

        $this->assertFalse($product->has_discount);
    }

    public function test_product_display_price(): void
    {
        $productWithSale = Product::factory()->create(['price' => 100, 'sale_price' => 80]);
        $productNoSale = Product::factory()->create(['price' => 100, 'sale_price' => null]);

        $this->assertEquals(80, $productWithSale->display_price);
        $this->assertEquals(100, $productNoSale->display_price);
    }

    public function test_product_in_stock(): void
    {
        $inStock = Product::factory()->create(['stock' => 10]);
        $outOfStock = Product::factory()->create(['stock' => 0]);

        $this->assertTrue($inStock->is_in_stock);
        $this->assertFalse($outOfStock->is_in_stock);
    }

    public function test_coupon_validity(): void
    {
        $activeCoupon = Coupon::factory()->create([
            'is_active' => true,
            'expires_at' => Carbon::now()->addDays(10),
            'max_uses' => 100,
            'used_count' => 5,
        ]);

        $expiredCoupon = Coupon::factory()->create([
            'is_active' => true,
            'expires_at' => Carbon::now()->subDay(),
        ]);

        $this->assertTrue($activeCoupon->isValid());
        $this->assertFalse($expiredCoupon->isValid());
    }

    public function test_coupon_fixed_discount_calculation(): void
    {
        $coupon = Coupon::factory()->create([
            'type' => 'fixed',
            'value' => 50,
            'is_active' => true,
            'max_uses' => null,
            'used_count' => 0,
            'min_order_amount' => null,
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        $discount = $coupon->calculateDiscount(200);

        $this->assertEquals(50, $discount);
    }

    public function test_coupon_percentage_discount_calculation(): void
    {
        $coupon = Coupon::factory()->create([
            'type' => 'percentage',
            'value' => 10,
            'max_discount' => 100,
            'is_active' => true,
            'max_uses' => null,
            'used_count' => 0,
            'expires_at' => Carbon::now()->addDays(30),
        ]);

        $discount = $coupon->calculateDiscount(2000);

        $this->assertEquals(100, $discount);
    }

    public function test_order_generates_valid_number(): void
    {
        $orderNumber = Order::generateOrderNumber();

        $this->assertStringStartsWith('EM-', $orderNumber);
        $this->assertEquals(17, strlen($orderNumber));
    }

    public function test_product_slug_generated(): void
    {
        $product = Product::factory()->create(['name' => 'Test Product Name', 'slug' => null]);

        $this->assertEquals('test-product-name', $product->slug);
    }

    public function test_category_slug_generated(): void
    {
        $category = Category::factory()->create(['name' => 'Test Category', 'slug' => null]);

        $this->assertEquals('test-category', $category->slug);
    }

    public function test_order_status_badge(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $this->assertStringContainsString('bg-yellow', $order->status_badge);
    }

    public function test_order_cancellable_only_when_pending_or_confirmed(): void
    {
        $pending = Order::factory()->create(['status' => 'pending']);
        $shipped = Order::factory()->create(['status' => 'shipped']);

        $this->assertTrue($pending->isCancellable());
        $this->assertFalse($shipped->isCancellable());
    }
}
