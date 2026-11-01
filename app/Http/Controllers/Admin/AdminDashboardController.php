<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalSales = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        $recentOrders = Order::with(['user'])
            ->latest()
            ->take(10)
            ->get();

        $lowStockProducts = Product::where('stock_quantity', '<=', 5)
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSales',
            'totalOrders',
            'pendingOrders',
            'totalCustomers',
            'totalProducts',
            'totalCategories',
            'recentOrders',
            'lowStockProducts'
        ));
    }
}
