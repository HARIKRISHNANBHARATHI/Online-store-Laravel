<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $query = Order::with(['user', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);
        $order->load(['user', 'items.product', 'payment', 'shippingAddress', 'billingAddress']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,delivered,cancelled'],
            'payment_status' => ['nullable', 'in:pending,paid,failed,refunded'],
        ]);

        $updateData = ['status' => $request->status];
        if ($request->filled('payment_status')) {
            $updateData['payment_status'] = $request->payment_status;
        }

        $order->update($updateData);

        return redirect()->back()->with('success', "Order #{$order->order_number} status updated to '{$request->status}'.");
    }
}
