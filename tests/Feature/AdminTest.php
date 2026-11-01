<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_user_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('ADMIN PANEL');
    }

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'New Test Product',
            'category_id' => $category->id,
            'sku' => 'TEST-1234',
            'price' => 49.99,
            'stock_quantity' => 20,
            'description' => 'Test Product Description',
            'status' => 1,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'sku' => 'TEST-1234',
            'name' => 'New Test Product',
        ]);
    }
}
