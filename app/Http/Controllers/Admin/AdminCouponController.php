<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCouponRequest;
use App\Http\Requests\UpdateCouponRequest;
use App\Models\Coupon;

class AdminCouponController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Coupon::class);
        $coupons = Coupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $this->authorize('create', Coupon::class);
        return view('admin.coupons.create');
    }

    public function store(StoreCouponRequest $request)
    {
        $this->authorize('create', Coupon::class);

        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $this->authorize('update', $coupon);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(UpdateCouponRequest $request, Coupon $coupon)
    {
        $this->authorize('update', $coupon);

        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $this->authorize('delete', $coupon);
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted.');
    }
}
