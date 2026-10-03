<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
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

    public function test_admin_can_create_product(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Test Product',
            'sku' => 'TST-001',
            'brand' => 'Test Brand',
            'price' => 100.00,
            'stock' => 10,
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['sku' => 'TST-001']);
    }

    public function test_customer_cannot_create_product(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->customer)->post('/admin/products', [
            'category_id' => $category->id,
            'name' => 'Test Product',
            'sku' => 'TST-002',
            'price' => 100.00,
            'stock' => 10,
            'status' => 'active',
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_update_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->put("/admin/products/{$product->slug}", [
            'category_id' => $product->category_id,
            'name' => 'Updated Name',
            'sku' => $product->sku,
            'price' => 150.00,
            'stock' => 20,
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['name' => 'Updated Name']);
    }

    public function test_admin_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/products/{$product->slug}");

        $response->assertRedirect();
        $this->assertSoftDeleted($product);
    }

    public function test_visitor_can_view_products(): void
    {
        Product::factory(3)->create(['status' => 'active']);

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertViewHas('products');
    }

    public function test_visitor_can_view_single_product(): void
    {
        $product = Product::factory()->create(['status' => 'active']);

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(200);
    }

    public function test_draft_product_not_visible(): void
    {
        $product = Product::factory()->create(['status' => 'draft']);

        $response = $this->get("/products/{$product->slug}");

        $response->assertStatus(404);
    }

    public function test_product_search_works(): void
    {
        Product::factory()->create(['name' => 'Nike Air Max', 'status' => 'active']);
        Product::factory()->create(['name' => 'Adidas Ultraboost', 'status' => 'active']);

        $response = $this->get('/products?search=Nike');

        $response->assertStatus(200);
        $response->assertSee('Nike Air Max');
        $response->assertDontSee('Adidas Ultraboost');
    }

    public function test_product_category_filter_works(): void
    {
        $category1 = Category::factory()->create(['slug' => 'shoes']);
        $category2 = Category::factory()->create(['slug' => 'bags']);
        Product::factory()->create(['category_id' => $category1->id, 'name' => 'Running Shoes', 'status' => 'active']);
        Product::factory()->create(['category_id' => $category2->id, 'name' => 'Leather Bag', 'status' => 'active']);

        $response = $this->get('/products?category=shoes');

        $response->assertSee('Running Shoes');
        $response->assertDontSee('Leather Bag');
    }
}
