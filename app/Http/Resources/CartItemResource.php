<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'product_name' => $this->product->name ?? 'Deleted Product',
            'product_sku' => $this->product->sku ?? '',
            'unit_price' => $this->product->effective_price ?? 0,
            'quantity' => $this->quantity,
            'subtotal' => $this->subtotal,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
