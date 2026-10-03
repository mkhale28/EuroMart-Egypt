<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Product;
    public function findBySlug(string $slug): ?Product;
    public function create(array $data): Product;
    public function update(Product $product, array $data): Product;
    public function delete(Product $product): bool;
    public function paginate(int $perPage = 12, array $filters = []): LengthAwarePaginator;
    public function getFeatured(int $limit = 8): Collection;
    public function getNewArrivals(int $limit = 8): Collection;
    public function getBestSellers(int $limit = 8): Collection;
    public function getRelatedProducts(Product $product, int $limit = 4): Collection;
    public function search(string $query, int $perPage = 12): LengthAwarePaginator;
    public function updateStock(Product $product, int $quantity): bool;
}
