<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface OrderRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Order;
    public function findByOrderNumber(string $orderNumber): ?Order;
    public function create(array $data): Order;
    public function updateStatus(Order $order, string $status): Order;
    public function getForUser(int $userId, int $perPage = 10): LengthAwarePaginator;
    public function paginate(int $perPage = 20, array $filters = []): LengthAwarePaginator;
    public function getRevenueByMonth(int $year): Collection;
    public function getTopSellingProducts(int $limit = 10): Collection;
    public function countByStatus(): Collection;
}
