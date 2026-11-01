<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'type' => $this->type,
            'value' => (float) $this->value,
            'minimum_order_amount' => $this->minimum_order_amount ? (float) $this->minimum_order_amount : null,
            'maximum_discount' => $this->maximum_discount ? (float) $this->maximum_discount : null,
            'usage_limit' => $this->usage_limit,
            'used_count' => $this->used_count,
            'starts_at' => $this->starts_at?->toIso8601String(),
            'expires_at' => $this->expires_at?->toIso8601String(),
            'status' => (bool) $this->status,
        ];
    }
}
