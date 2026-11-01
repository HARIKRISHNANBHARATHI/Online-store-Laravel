<?php

namespace Tests\Feature;

use App\Events\OrderPlaced;
use App\Models\Address;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_place_cod_order(): void
    {
        Event::fake([OrderPlaced::class]);

        $user = User::factory()->create();
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'category_id' => $category->id,
            'price' => 100.00,
            'stock_quantity' => 10,
        ]);

        $address = Address::factory()->create(['user_id' => $user->id, 'is_default' => true]);

        // Step 1: Add item to cart
        $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // Step 2: Process checkout (COD → order placed directly)
        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'shipping_address_id' => $address->id,
            'payment_method' => 'cod',
        ]);

        // Order should exist in DB
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'status' => 'processing',
            'payment_method' => 'cod',
            'payment_status' => 'pending',
        ]);

        $order = \App\Models\Order::where('user_id', $user->id)->firstOrFail();

        // Should redirect straight to success
        $response->assertRedirect(route('checkout.success', $order->id));

        // Cart should be empty after checkout
        $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);

        // Inventory should have been decremented
        $product->refresh();
        $this->assertEquals(8, $product->stock_quantity);

        // OrderPlaced event should have fired
        Event::assertDispatched(OrderPlaced::class);
    }

    public function test_guest_is_redirected_to_login_from_checkout(): void
    {
        $response = $this->get(route('checkout.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_checkout_redirects_to_cart_when_cart_is_empty(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('checkout.index'));
        $response->assertRedirect(route('cart.index'));
    }
}
