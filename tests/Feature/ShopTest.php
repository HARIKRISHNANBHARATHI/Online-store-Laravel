<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_shop_page_with_products(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_can_filter_products_by_category(): void
    {
        $cat1 = Category::factory()->create(['name' => 'Electronics']);
        $cat2 = Category::factory()->create(['name' => 'Clothing']);

        $prod1 = Product::factory()->create(['category_id' => $cat1->id, 'name' => 'Smartphone']);
        $prod2 = Product::factory()->create(['category_id' => $cat2->id, 'name' => 'T-Shirt']);

        $response = $this->get(route('products.index', ['category_id' => $cat1->id]));

        $response->assertStatus(200);
        $response->assertSee('Smartphone');
        $response->assertDontSee('T-Shirt');
    }

    public function test_can_view_single_product_detail_page(): void
    {
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee($product->sku);
    }
}
