<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function index()
    {
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return $this->orderService->getDashboardStats();
        });

        $revenueByMonth = Cache::remember('revenue_by_month', 3600, function () {
            $driver = (new Order)->getConnection()->getDriverName();
            $monthExpr = $driver === 'sqlite'
                ? "strftime('%m', created_at) as month"
                : 'MONTH(created_at) as month';
            $groupExpr = $driver === 'sqlite'
                ? "strftime('%m', created_at)"
                : 'MONTH(created_at)';

            return Order::selectRaw("{$monthExpr}, SUM(total) as revenue, COUNT(*) as orders")
                ->whereYear('created_at', now()->year)
                ->where('status', '!=', 'cancelled')
                ->groupByRaw($groupExpr)
                ->orderByRaw($groupExpr)
                ->get();
        });

        $topProducts = Cache::remember('top_products', 3600, function () {
            return Product::select('id', 'name', 'slug', 'sold_count', 'stock')
                ->where('status', 'active')
                ->orderBy('sold_count', 'desc')
                ->take(10)
                ->get();
        });

        return view('admin.dashboard.index', compact('stats', 'revenueByMonth', 'topProducts'));
    }
}
