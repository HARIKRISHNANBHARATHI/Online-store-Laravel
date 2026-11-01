<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_product_to_cart(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'stock_quantity' => 10]);

        $response = $this->actingAs($user)
            ->from(route('products.show', $product->slug))
            ->post(route('cart.add'), [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);

        // Redirects back to product page
        $response->assertRedirect(route('products.show', $product->slug));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_user_can_apply_valid_coupon(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id, 'price' => 50.00]);

        // Add product to cart first
        $this->actingAs($user)->from('/cart')->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        Coupon::factory()->create([
            'code'   => 'SAVE10',
            'type'   => 'fixed',
            'value'  => 10,
            'status' => true,
        ]);

        $response = $this->actingAs($user)->post(route('cart.coupon.apply'), [
            'coupon_code' => 'SAVE10',
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success');
    }

    public function test_user_can_remove_item_from_cart(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create(['category_id' => $category->id]);

        $this->actingAs($user)->from('/products')->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $cartItem = \App\Models\CartItem::where('product_id', $product->id)->first();
        $this->assertNotNull($cartItem);

        $response = $this->actingAs($user)->delete(route('cart.remove', $cartItem->id));
        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
