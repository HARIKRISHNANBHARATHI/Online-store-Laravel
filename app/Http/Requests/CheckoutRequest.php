<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'shipping_address_id' => ['nullable', 'exists:addresses,id'],
            'billing_address_id' => ['nullable', 'exists:addresses,id'],
            // Direct address creation fields if user is adding new address on checkout
            'new_address' => ['nullable', 'array'],
            'new_address.first_name' => ['required_with:new_address', 'string', 'max:100'],
            'new_address.last_name' => ['required_with:new_address', 'string', 'max:100'],
            'new_address.address_line_1' => ['required_with:new_address', 'string', 'max:255'],
            'new_address.address_line_2' => ['nullable', 'string', 'max:255'],
            'new_address.city' => ['required_with:new_address', 'string', 'max:100'],
            'new_address.state' => ['required_with:new_address', 'string', 'max:100'],
            'new_address.postal_code' => ['required_with:new_address', 'string', 'max:20'],
            'new_address.country' => ['required_with:new_address', 'string', 'max:100'],
            'new_address.phone' => ['required_with:new_address', 'string', 'max:30'],
        ];
    }
}
