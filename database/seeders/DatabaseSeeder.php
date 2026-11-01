<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Store Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Customer User
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'John Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
                'email_verified_at' => now(),
            ]
        );

        // 3. Create Default Address for Customer
        $customerAddress = Address::firstOrCreate(
            ['user_id' => $customer->id, 'address_line_1' => '123 Tech Boulevard'],
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'address_line_2' => 'Suite 400',
                'city' => 'San Francisco',
                'state' => 'CA',
                'postal_code' => '94105',
                'country' => 'United States',
                'phone' => '+1 (555) 019-2834',
                'type' => 'both',
                'is_default' => true,
            ]
        );

        // 4. Create Categories
        $categoriesData = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Latest gadgets, laptops, smartphones, and audio accessories.',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Fashion & Apparel',
                'slug' => 'fashion-apparel',
                'description' => 'Trendy clothing, shoes, jackets, and fashion accessories.',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Home & Living',
                'slug' => 'home-living',
                'description' => 'Modern furniture, decor, lighting, and home accessories.',
                'image' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f?w=600&auto=format&fit=crop&q=80',
            ],
            [
                'name' => 'Sports & Fitness',
                'slug' => 'sports-fitness',
                'description' => 'Fitness gear, gym equipment, activewear, and outdoor sports.',
                'image' => 'https://images.unsplash.com/photo-1517838277536-f5f99be501cd?w=600&auto=format&fit=crop&q=80',
            ],
        ];

        $categories = [];
        foreach ($categoriesData as $catData) {
            $categories[$catData['slug']] = Category::firstOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'description' => $catData['description'],
                    'status' => true,
                ]
            );
        }

        // 5. Create Products with Images
        $productsData = [
            [
                'category_slug' => 'electronics',
                'name' => 'Wireless Noise-Canceling Headphones',
                'sku' => 'ELEC-WNC-001',
                'description' => 'Premium wireless over-ear headphones featuring advanced active noise cancellation, 30-hour battery life, crisp high-fidelity audio output, and ultra-comfortable memory foam earcups.',
                'price' => 299.99,
                'discount_price' => 249.99,
                'stock_quantity' => 25,
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1484704849700-f032a568e944?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'electronics',
                'name' => 'Ultra Slim Smartwatch Series 7',
                'sku' => 'ELEC-SMW-002',
                'description' => 'Sleek fitness and smartwatch with vibrant AMOLED display, heart rate monitor, sleep tracking, GPS, 50m water resistance, and customizable watch faces.',
                'price' => 199.00,
                'discount_price' => 169.00,
                'stock_quantity' => 40,
                'images' => [
                    'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'electronics',
                'name' => 'Ergonomic Wireless Mechanical Keyboard',
                'sku' => 'ELEC-KBD-003',
                'description' => 'Tactile mechanical switches with customizable RGB backlighting, Bluetooth 5.0 multi-device pairing, aluminum chassis, and detachable wrist rest.',
                'price' => 129.50,
                'discount_price' => null,
                'stock_quantity' => 15,
                'images' => [
                    'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'fashion-apparel',
                'name' => 'Classic Leather Biker Jacket',
                'sku' => 'FASH-JKT-001',
                'description' => 'Handcrafted genuine leather jacket featuring asymmetrical zip closure, quilted shoulder detail, soft viscose lining, and classic biker styling.',
                'price' => 189.99,
                'discount_price' => 149.99,
                'stock_quantity' => 10,
                'images' => [
                    'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'fashion-apparel',
                'name' => 'Minimalist Urban Running Sneakers',
                'sku' => 'FASH-SNK-002',
                'description' => 'Lightweight mesh upper sneakers with responsive cushion midsole, breathable design, and durable rubber outsole for all-day urban comfort.',
                'price' => 89.95,
                'discount_price' => 74.99,
                'stock_quantity' => 50,
                'images' => [
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'home-living',
                'name' => 'Scandinavian Nordic Desk Lamp',
                'sku' => 'HOME-LMP-001',
                'description' => 'Architectural LED desk lamp with solid oak wood arm, matte black metal shade, touch control brightness, and color temperature adjustment.',
                'price' => 75.00,
                'discount_price' => null,
                'stock_quantity' => 30,
                'images' => [
                    'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'home-living',
                'name' => 'Ceramic Minimalist Pour-Over Coffee Set',
                'sku' => 'HOME-COF-002',
                'description' => 'Hand-made ceramic coffee dripper and glass carafe set designed for optimal thermal stability and clean pour-over brewing experience.',
                'price' => 45.00,
                'discount_price' => 38.00,
                'stock_quantity' => 20,
                'images' => [
                    'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'category_slug' => 'sports-fitness',
                'name' => 'Non-Slip Eco Yoga Mat & Strap',
                'sku' => 'SPRT-YGA-001',
                'description' => 'Extra-thick non-slip TPE eco-friendly yoga mat with alignment line markings, moisture-resistant surface, and cotton carrying strap.',
                'price' => 49.99,
                'discount_price' => 39.99,
                'stock_quantity' => 60,
                'images' => [
                    'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=800&auto=format&fit=crop&q=80',
                ],
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $pData) {
            $category = $categories[$pData['category_slug']];

            $product = Product::firstOrCreate(
                ['sku' => $pData['sku']],
                [
                    'category_id' => $category->id,
                    'name' => $pData['name'],
                    'slug' => Str::slug($pData['name']),
                    'description' => $pData['description'],
                    'price' => $pData['price'],
                    'discount_price' => $pData['discount_price'],
                    'stock_quantity' => $pData['stock_quantity'],
                    'status' => true,
                ]
            );

            $createdProducts[] = $product;

            foreach ($pData['images'] as $idx => $imgUrl) {
                ProductImage::firstOrCreate(
                    ['product_id' => $product->id, 'image_path' => $imgUrl],
                    ['is_primary' => $idx === 0]
                );
            }
        }

        // 6. Create Coupons
        Coupon::firstOrCreate(
            ['code' => 'WELCOME10'],
            [
                'type' => 'percentage',
                'value' => 10.00,
                'minimum_order_amount' => 30.00,
                'maximum_discount' => 50.00,
                'usage_limit' => 500,
                'used_count' => 12,
                'starts_at' => now()->subMonth(),
                'expires_at' => now()->addYear(),
                'status' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'FLAT20'],
            [
                'type' => 'fixed',
                'value' => 20.00,
                'minimum_order_amount' => 100.00,
                'usage_limit' => 200,
                'used_count' => 45,
                'starts_at' => now()->subMonth(),
                'expires_at' => now()->addMonths(6),
                'status' => true,
            ]
        );

        Coupon::firstOrCreate(
            ['code' => 'SUMMER25'],
            [
                'type' => 'percentage',
                'value' => 25.00,
                'minimum_order_amount' => 150.00,
                'maximum_discount' => 75.00,
                'usage_limit' => 100,
                'used_count' => 88,
                'starts_at' => now()->subMonth(),
                'expires_at' => now()->addMonths(3),
                'status' => true,
            ]
        );

        // 7. Create Sample Past Orders
        if (Order::where('user_id', $customer->id)->count() === 0) {
            $p1 = $createdProducts[0];
            $p2 = $createdProducts[1];

            $subtotal = $p1->effective_price + $p2->effective_price;
            $discount = 20.00;
            $tax = round(($subtotal - $discount) * 0.08, 2);
            $shipping = 0.00; // free over 100
            $total = round($subtotal - $discount + $tax + $shipping, 2);

            $order = Order::create([
                'user_id' => $customer->id,
                'billing_address_id' => $customerAddress->id,
                'shipping_address_id' => $customerAddress->id,
                'order_number' => Order::generateOrderNumber(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'status' => 'delivered',
                'payment_status' => 'paid',
                'coupon_code' => 'FLAT20',
                'billing_address_data' => $customerAddress->toArray(),
                'shipping_address_data' => $customerAddress->toArray(),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $p1->id,
                'product_name' => $p1->name,
                'quantity' => 1,
                'price' => $p1->effective_price,
                'subtotal' => $p1->effective_price,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $p2->id,
                'product_name' => $p2->name,
                'quantity' => 1,
                'price' => $p2->effective_price,
                'subtotal' => $p2->effective_price,
            ]);

            Payment::create([
                'order_id' => $order->id,
                'transaction_id' => 'pi_stripe_demo_' . Str::random(10),
                'provider' => 'stripe',
                'amount' => $total,
                'currency' => 'USD',
                'status' => 'succeeded',
                'paid_at' => now()->subDays(2),
            ]);
        }
    }
}
