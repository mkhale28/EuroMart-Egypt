<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'brand' => $this->brand,
            'price' => (float) $this->price,
            'sale_price' => (float) $this->sale_price,
            'display_price' => $this->display_price,
            'has_discount' => $this->has_discount,
            'discount_percentage' => $this->discount_percentage,
            'is_in_stock' => $this->is_in_stock,
            'featured' => $this->featured,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'primary_image' => new ProductImageResource($this->whenLoaded('primaryImage')),
            'average_rating' => $this->average_rating,
            'sold_count' => $this->sold_count,
            'created_at' => $this->created_at,
        ];
    }
}
