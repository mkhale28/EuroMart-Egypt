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
            'category_id' => $this->category_id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'sku' => $this->sku,
            'brand' => $this->brand,
            'price' => (float) $this->price,
            'sale_price' => (float) $this->sale_price,
            'display_price' => $this->display_price,
            'has_discount' => $this->has_discount,
            'discount_percentage' => $this->discount_percentage,
            'stock' => $this->stock,
            'is_in_stock' => $this->is_in_stock,
            'featured' => $this->featured,
            'status' => $this->status,
            'images' => ProductImageResource::collection($this->whenLoaded('images')),
            'primary_image' => new ProductImageResource($this->whenLoaded('primaryImage')),
            'reviews' => ReviewResource::collection($this->whenLoaded('approvedReviews')),
            'average_rating' => $this->average_rating,
            'reviews_count' => $this->approvedReviews->count() ?? 0,
            'view_count' => $this->view_count,
            'sold_count' => $this->sold_count,
            'meta' => [
                'title' => $this->meta_title,
                'description' => $this->meta_description,
                'keywords' => $this->meta_keywords,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
