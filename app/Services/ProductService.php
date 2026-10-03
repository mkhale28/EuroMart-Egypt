<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function create(array $data, ?array $images = null): Product
    {
        $product = $this->productRepository->create($data);

        if ($images) {
            $this->uploadImages($product, $images);
        }

        return $product->load('images', 'category');
    }

    public function update(Product $product, array $data, ?array $images = null): Product
    {
        $product = $this->productRepository->update($product, $data);

        if ($images) {
            $this->uploadImages($product, $images);
        }

        return $product->load('images', 'category');
    }

    public function uploadImages(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            if ($image instanceof UploadedFile) {
                $filename = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('products', $filename, 'public');

                $product->images()->create([
                    'image' => $filename,
                    'is_primary' => $index === 0 && $product->images()->count() === 0,
                    'sort_order' => $index,
                ]);
            }
        }
    }

    public function deleteImage(int $imageId): bool
    {
        $image = \App\Models\ProductImage::find($imageId);
        if (!$image) return false;

        Storage::disk('public')->delete('products/' . $image->image);
        return $image->delete();
    }
}
