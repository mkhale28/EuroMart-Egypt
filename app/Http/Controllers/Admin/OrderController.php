<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private OrderService $orderService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'search', 'date_from', 'date_to']);
        $orders = $this->orderRepository->paginate(20, $filters);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'cancellation_reason' => 'required_if:status,cancelled|nullable|string|max:500',
        ]);

        $this->orderService->updateStatus(
            $order,
            $request->status,
            $request->cancellation_reason
        );

        return back()->with('success', 'Order status updated successfully.');
    }

    public function invoice(Order $order)
    {
        $pdf = $this->orderService->generateInvoice($order);
        return $pdf->download("invoice-{$order->order_number}.pdf");
    }

    public function print(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.print', compact('order'));
    }
}
