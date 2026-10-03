<?php

namespace App\Services\AI\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AISearchServiceInterface
{
    public function search(string $query, array $filters = [], int $perPage = 12): LengthAwarePaginator;
    public function parseNaturalLanguage(string $query): array;
}
