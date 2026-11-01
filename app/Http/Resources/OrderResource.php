<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'tax' => (float) $this->tax,
            'shipping' => (float) $this->shipping,
            'total' => (float) $this->total,
            'coupon_code' => $this->coupon_code,
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'shipping_address' => $this->shipping_address_data,
            'billing_address' => $this->billing_address_data,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
