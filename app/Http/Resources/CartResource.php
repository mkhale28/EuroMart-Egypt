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
            'product_id' => $this->product_id,
            'product' => new ProductListResource($this->whenLoaded('product')),
            'quantity' => $this->quantity,
            'price' => (float) $this->product->display_price,
            'subtotal' => $this->subtotal,
        ];
    }
}
