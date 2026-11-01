<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'coupon_code' => $this->coupon_code,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'tax' => (float) $this->tax,
            'shipping' => (float) $this->shipping,
            'total' => (float) $this->total,
        ];
    }
}
