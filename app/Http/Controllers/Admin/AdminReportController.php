<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $orders = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('payment_status', 'paid');

        $totalRevenue = (clone $orders)->sum('total');
        $totalOrdersCount = (clone $orders)->count();
        $averageOrderValue = $totalOrdersCount > 0 ? $totalRevenue / $totalOrdersCount : 0;

        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select('order_items.product_name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('SUM(order_items.subtotal) as total_sales'))
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_sales')
            ->take(5)
            ->get();

        return view('admin.reports.index', compact('totalRevenue', 'totalOrdersCount', 'averageOrderValue', 'topProducts', 'startDate', 'endDate'));
    }
}
