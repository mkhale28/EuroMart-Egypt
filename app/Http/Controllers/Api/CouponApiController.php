<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Services\CartService;
use App\Services\CheckoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponApiController extends Controller
{
    public function __construct(
        private CheckoutService $checkoutService,
        private CartService $cartService
    ) {}

    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate(['code' => 'required|string|max:50']);

        $result = $this->checkoutService->validateCoupon(
            $request->code,
            $this->cartService->subtotal()
        );

        return response()->json($result);
    }
}
