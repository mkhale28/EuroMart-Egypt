<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Coupon;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cartService,
        private CheckoutService $checkoutService
    ) {}

    public function index()
    {
        $cartItems = $this->cartService->getItems();
        $subtotal = $this->cartService->subtotal();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('customer.checkout.index', compact('cartItems', 'subtotal'));
    }

    public function process(CheckoutRequest $request)
    {
        try {
            $order = $this->checkoutService->process(
                $request->validated(),
                $request->coupon_code
            );

            return redirect()
                ->route('orders.confirmation', $order->order_number)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function validateCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string|max:50']);

        $result = $this->checkoutService->validateCoupon(
            $request->code,
            $this->cartService->subtotal()
        );

        return response()->json($result);
    }
}
