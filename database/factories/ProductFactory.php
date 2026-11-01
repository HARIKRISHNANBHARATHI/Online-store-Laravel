<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        $price = fake()->randomFloat(2, 10, 500);
        $hasDiscount = fake()->boolean(40);
        $discountPrice = $hasDiscount ? round($price * 0.8, 2) : null;

        return [
            'category_id' => Category::factory(),
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . Str::random(4),
            'sku' => 'SKU-' . strtoupper(Str::random(8)),
            'description' => fake()->paragraphs(2, true),
            'price' => $price,
            'discount_price' => $discountPrice,
            'stock_quantity' => fake()->numberBetween(5, 100),
            'status' => true,
        ];
    }
}
