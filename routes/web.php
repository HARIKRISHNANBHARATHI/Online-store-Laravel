<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

// Home & Catalog Routes
Route::get('/', [ShopController::class, 'index'])->name('home');
Route::get('/products', [ShopController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ShopController::class, 'show'])->name('products.show');
Route::get('/categories/{slug}', [ShopController::class, 'category'])->name('categories.show');

// Shopping Cart Routes
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/items/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/items/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::post('/coupon', [CartController::class, 'applyCoupon'])->name('coupon.apply');
    Route::delete('/coupon', [CartController::class, 'removeCoupon'])->name('coupon.remove');
});

// Authenticated Customer Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard & Profile
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('orders.index');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/toggle/{product}', [WishlistController::class, 'toggle'])->name('toggle');
        Route::delete('/remove/{product}', [WishlistController::class, 'remove'])->name('remove');
    });

    // Customer Addresses
    Route::resource('addresses', AddressController::class);
    Route::post('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');

    // Checkout Flow (Cash on Delivery — no payment gateway step)
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [CheckoutController::class, 'process'])->name('process');
        Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
    });

    // Orders History
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Admin Panel Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::resource('products', AdminProductController::class);
    Route::delete('/products/images/{image}', [AdminProductController::class, 'deleteImage'])->name('products.images.destroy');
    Route::post('/products/images/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.primary');

    // Categories Management
    Route::resource('categories', AdminCategoryController::class);

    // Orders Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Customers Management
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');

    // Coupons Management
    Route::resource('coupons', AdminCouponController::class);

    // Sales Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
});

require __DIR__.'/auth.php';
