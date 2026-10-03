<?php

namespace App\Services\AI;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\AI\Contracts\AISearchServiceInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AISearchService implements AISearchServiceInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function search(string $query, array $filters = [], int $perPage = 12): LengthAwarePaginator
    {
        // Phase 2: Integrate with OpenAI/Meilisearch for semantic search
        return $this->productRepository->search($query, $perPage);
    }

    public function parseNaturalLanguage(string $query): array
    {
        // Phase 2: Parse natural language queries like "black shoes under 2500 EGP"
        $filters = [];
        
        // Basic price extraction
        if (preg_match('/(\d+)\s*(egp|le|\$)/i', $query, $matches)) {
            $filters['max_price'] = (float) $matches[1];
        }

        return $filters;
    }
}
