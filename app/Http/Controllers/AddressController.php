<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = auth()->user()->addresses()->latest()->get();
        return view('addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('addresses.create');
    }

    public function store(StoreAddressRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        if (!empty($data['is_default'])) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address = $user->addresses()->create($data);

        return redirect()->route('addresses.index')->with('success', 'Address saved successfully.');
    }

    public function edit(Address $address)
    {
        $this->authorize('update', $address);
        return view('addresses.edit', compact('address'));
    }

    public function update(UpdateAddressRequest $request, Address $address)
    {
        $this->authorize('update', $address);

        $data = $request->validated();

        if (!empty($data['is_default'])) {
            auth()->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($data);

        return redirect()->route('addresses.index')->with('success', 'Address updated successfully.');
    }

    public function destroy(Address $address)
    {
        $this->authorize('delete', $address);
        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Address deleted successfully.');
    }

    public function setDefault(Address $address)
    {
        $this->authorize('update', $address);

        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('addresses.index')->with('success', 'Default address set.');
    }
}
