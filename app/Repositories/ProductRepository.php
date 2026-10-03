<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): Collection
    {
        return Product::with('category', 'primaryImage', 'images')
            ->active()
            ->latest()
            ->get();
    }

    public function find(int $id): ?Product
    {
        return Product::with('category', 'primaryImage', 'images', 'approvedReviews')
            ->find($id);
    }

    public function findBySlug(string $slug): ?Product
    {
        return Product::with('category', 'primaryImage', 'images', 'approvedReviews')
            ->where('slug', $slug)
            ->first();
    }

    public function create(array $data): Product
    {
        Cache::tags(['products'])->flush();
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        Cache::tags(['products'])->flush();
        $product->update($data);
        return $product->fresh();
    }

    public function delete(Product $product): bool
    {
        Cache::tags(['products'])->flush();
        return $product->delete();
    }

    public function paginate(int $perPage = 12, array $filters = []): LengthAwarePaginator
    {
        $query = Product::with('category', 'primaryImage')
            ->active();

        if (!empty($filters['category'])) {
            $query->whereHas('category', fn($q) => $q->where('slug', $filters['category']));
        }

        if (!empty($filters['brand'])) {
            $query->where('brand', $filters['brand']);
        }

        if (!empty($filters['min_price']) && !empty($filters['max_price'])) {
            $query->filterByPrice($filters['min_price'], $filters['max_price']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%")
                  ->orWhere('brand', 'like', "%{$filters['search']}%");
            });
        }

        if (!empty($filters['featured'])) {
            $query->featured();
        }

        if (!empty($filters['on_sale'])) {
            $query->onSale();
        }

        $sort = $filters['sort'] ?? 'latest';
        $query->sortBy($sort);

        return $query->paginate($perPage);
    }

    public function getFeatured(int $limit = 8): Collection
    {
        return Cache::tags(['products'])->remember('featured_products_' . $limit, 3600, function () use ($limit) {
            return Product::with('category', 'primaryImage')
                ->active()
                ->featured()
                ->inStock()
                ->latest()
                ->take($limit)
                ->get();
        });
    }

    public function getNewArrivals(int $limit = 8): Collection
    {
        return Cache::tags(['products'])->remember('new_arrivals_' . $limit, 3600, function () use ($limit) {
            return Product::with('category', 'primaryImage')
                ->active()
                ->inStock()
                ->latest()
                ->take($limit)
                ->get();
        });
    }

    public function getBestSellers(int $limit = 8): Collection
    {
        return Cache::tags(['products'])->remember('best_sellers_' . $limit, 3600, function () use ($limit) {
            return Product::with('category', 'primaryImage')
                ->active()
                ->inStock()
                ->orderBy('sold_count', 'desc')
                ->take($limit)
                ->get();
        });
    }

    public function getRelatedProducts(Product $product, int $limit = 4): Collection
    {
        return Product::with('category', 'primaryImage')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take($limit)
            ->get();
    }

    public function search(string $query, int $perPage = 12): LengthAwarePaginator
    {
        return Product::with('category', 'primaryImage')
            ->active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('brand', 'like', "%{$query}%");
            })
            ->paginate($perPage);
    }

    public function updateStock(Product $product, int $quantity): bool
    {
        Cache::tags(['products'])->flush();
        return $product->decrement('stock', $quantity);
    }
}
