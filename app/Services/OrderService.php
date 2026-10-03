<?php

namespace App\Services;

use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderService
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function updateStatus(Order $order, string $status, ?string $reason = null): Order
    {
        $oldStatus = $order->status;
        $order = $this->orderRepository->updateStatus($order, $status);

        if ($status === 'cancelled') {
            $order->update(['cancellation_reason' => $reason]);
        }

        event(new OrderStatusChanged($order, $oldStatus, $status));

        return $order;
    }

    public function generateInvoice(Order $order): \Barryvdh\DomPDF\PDF
    {
        $order->load('items.product');
        return Pdf::loadView('admin.orders.invoice', compact('order'));
    }

    public function getDashboardStats(): array
    {
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');
        $totalOrders = Order::count();
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
        $totalProducts = \App\Models\Product::active()->count();

        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total');

        $ordersThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $pendingOrders = Order::pending()->count();
        $recentOrders = Order::with('items', 'user')
            ->latest()
            ->take(5)
            ->get();

        return compact(
            'totalRevenue', 'totalOrders', 'totalCustomers', 'totalProducts',
            'revenueThisMonth', 'ordersThisMonth', 'pendingOrders', 'recentOrders'
        );
    }
}
