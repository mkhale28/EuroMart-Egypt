<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function confirmation($orderNumber)
    {
        $order = $this->orderRepository->findByOrderNumber($orderNumber);

        if (!$order) {
            abort(404);
        }

        return view('customer.orders.confirmation', compact('order'));
    }

    public function trackForm()
    {
        return view('customer.orders.track');
    }

    public function track(Request $request)
    {
        $request->validate(['order_number' => 'required|string|max:50']);

        $order = $this->orderRepository->findByOrderNumber($request->order_number);

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        return view('customer.orders.track', compact('order'));
    }

    public function cancel(Order $order)
    {
        $this->authorize('cancel', $order);

        if (!$order->isCancellable()) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        $order->update(['status' => 'cancelled', 'cancelled_at' => now()]);

        return back()->with('success', 'Order cancelled successfully.');
    }
}
