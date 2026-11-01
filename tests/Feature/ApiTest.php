<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_can_list_products(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => ['id', 'name', 'slug', 'sku', 'price', 'effective_price', 'category']
            ]
        ]);
    }

    public function test_api_user_authentication_flow(): void
    {
        $user = User::factory()->create([
            'email' => 'apitest@example.com',
            'password' => bcrypt('password123'),
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'apitest@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200);
        $loginResponse->assertJsonStructure(['access_token', 'user']);

        $token = $loginResponse->json('access_token');

        $meResponse = $this->withToken($token)->getJson('/api/user');
        $meResponse->assertStatus(200);
        $meResponse->assertJsonPath('user.email', 'apitest@example.com');
    }

    public function test_api_cart_management(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $token = $user->createToken('test')->plainTextToken;

        $addResponse = $this->withToken($token)->postJson('/api/cart/items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $addResponse->assertStatus(200);

        $getCartResponse = $this->withToken($token)->getJson('/api/cart');
        $getCartResponse->assertStatus(200);
        $getCartResponse->assertJsonStructure(['data' => ['items', 'summary']]);
    }
}
