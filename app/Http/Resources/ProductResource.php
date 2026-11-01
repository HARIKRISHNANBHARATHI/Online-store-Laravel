<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'description' => $this->description,
            'price' => (float) $this->price,
            'discount_price' => $this->discount_price ? (float) $this->discount_price : null,
            'effective_price' => $this->effective_price,
            'stock_quantity' => $this->stock_quantity,
            'is_in_stock' => $this->is_in_stock,
            'status' => (bool) $this->status,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'images' => $this->images->map(fn($img) => [
                'id' => $img->id,
                'url' => $img->url,
                'is_primary' => $img->is_primary,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
