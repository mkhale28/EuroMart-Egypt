<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function __construct(
        private CartService $cartService,
        private OrderRepositoryInterface $orderRepository
    ) {}

    public function process(array $data, ?string $couponCode = null): Order
    {
        return DB::transaction(function () use ($data, $couponCode) {
            $cartItems = $this->cartService->getItems();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Cart is empty');
            }

            $subtotal = $this->cartService->subtotal();
            $discount = 0;
            $couponDiscount = 0;

            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon && $coupon->isValid()) {
                    $couponDiscount = $coupon->calculateDiscount($subtotal);
                    $coupon->increment('used_count');
                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => auth()->id(),
                        'discount_amount' => $couponDiscount,
                    ]);
                }
            }

            $total = $subtotal - $couponDiscount;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => auth()->id(),
                'customer_name' => $data['full_name'],
                'customer_phone' => $data['phone'],
                'customer_email' => auth()->user()?->email,
                'governorate' => $data['governorate'],
                'address' => $data['address'],
                'notes' => $data['notes'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping' => 0,
                'total' => $total,
                'coupon_code' => $couponCode,
                'coupon_discount' => $couponDiscount,
                'payment_method' => $data['payment_method'],
                'status' => 'pending',
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku,
                    'price' => $cartItem->product->display_price,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->product->display_price * $cartItem->quantity,
                ]);

                $cartItem->product->increment('sold_count', $cartItem->quantity);
            }

            $this->cartService->clear();

            return $order->load('items');
        });
    }

    public function validateCoupon(string $code, float $subtotal): array
    {
        $coupon = Coupon::where('code', $code)->first();

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Invalid coupon code'];
        }

        if (!$coupon->isValid()) {
            return ['valid' => false, 'message' => 'Coupon is expired or inactive'];
        }

        if ($coupon->min_order_amount && $subtotal < $coupon->min_order_amount) {
            return ['valid' => false, 'message' => 'Minimum order amount not met'];
        }

        $discount = $coupon->calculateDiscount($subtotal);

        return [
            'valid' => true,
            'discount' => $discount,
            'message' => "Coupon applied! You save " . number_format($discount, 2) . " EGP",
        ];
    }
}
