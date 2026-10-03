<?php

namespace App\Services\AI\Contracts;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface RecommendationServiceInterface
{
    public function getRecommendationsForProduct(Product $product, int $limit = 6): Collection;
    public function getPersonalizedRecommendations(int $userId, int $limit = 8): Collection;
    public function getTrendingProducts(int $limit = 8): Collection;
    public function getFrequentlyBoughtTogether(Product $product, int $limit = 4): Collection;
}
