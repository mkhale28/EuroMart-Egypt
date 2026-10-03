<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->admin()->create();
        $this->customer = User::factory()->create();
    }

    public function test_admin_can_view_all_orders(): void
    {
        Order::factory(3)->create();

        $response = $this->actingAs($this->admin)->get('/admin/orders');

        $response->assertStatus(200);
        $response->assertViewHas('orders');
    }

    public function test_customer_can_view_own_orders(): void
    {
        Order::factory()->create(['user_id' => $this->customer->id]);
        Order::factory()->create();

        $response = $this->actingAs($this->customer)->get('/dashboard/orders');

        $response->assertStatus(200);
    }

    public function test_admin_can_update_order_status(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $response = $this->actingAs($this->admin)
            ->patch("/admin/orders/{$order->id}/status", ['status' => 'confirmed']);

        $response->assertRedirect();
        $this->assertEquals('confirmed', $order->fresh()->status);
    }

    public function test_order_status_has_timestamps(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        $this->actingAs($this->admin)
            ->patch("/admin/orders/{$order->id}/status", ['status' => 'confirmed']);

        $this->assertNotNull($order->fresh()->confirmed_at);
    }

    public function test_order_number_format(): void
    {
        $order = Order::factory()->create();

        $this->assertStringStartsWith('EM-', $order->order_number);
    }

    public function test_customer_can_track_order(): void
    {
        $order = Order::factory()->create(['order_number' => 'EM-20240101-0001']);

        $response = $this->post('/orders/track', ['order_number' => 'EM-20240101-0001']);

        $response->assertStatus(200);
        $response->assertSee($order->order_number);
    }
}
