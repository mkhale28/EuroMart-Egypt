<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected ?string $sessionId;

    public function __construct()
    {
        $this->sessionId = Session::getId();
    }

    public function getItems(): Collection
    {
        return Cart::with('product.primaryImage', 'product.category')
            ->where(function ($q) {
                if (auth()->check()) {
                    $q->where('user_id', auth()->id());
                } else {
                    $q->where('session_id', $this->sessionId);
                }
            })
            ->get();
    }

    public function add(Product $product, int $quantity = 1): Cart
    {
        $existing = Cart::where('product_id', $product->id)
            ->where(function ($q) {
                if (auth()->check()) {
                    $q->where('user_id', auth()->id());
                } else {
                    $q->where('session_id', $this->sessionId);
                }
            })
            ->first();

        if ($existing) {
            $existing->increment('quantity', $quantity);
            return $existing->fresh();
        }

        return Cart::create([
            'user_id' => auth()->id(),
            'session_id' => auth()->check() ? null : $this->sessionId,
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);
    }

    public function update(int $cartId, int $quantity): bool
    {
        $item = $this->getItem($cartId);
        if (!$item) return false;

        return $item->update(['quantity' => max(1, $quantity)]);
    }

    public function remove(int $cartId): bool
    {
        $item = $this->getItem($cartId);
        if (!$item) return false;

        return $item->delete();
    }

    public function clear(): void
    {
        Cart::where(function ($q) {
            if (auth()->check()) {
                $q->where('user_id', auth()->id());
            } else {
                $q->where('session_id', $this->sessionId);
            }
        })->delete();
    }

    public function count(): int
    {
        return Cart::where(function ($q) {
            if (auth()->check()) {
                $q->where('user_id', auth()->id());
            } else {
                $q->where('session_id', $this->sessionId);
            }
        })->sum('quantity');
    }

    public function subtotal(): float
    {
        return $this->getItems()->sum(function ($item) {
            return $item->product->display_price * $item->quantity;
        });
    }

    public function mergeGuestCart(): void
    {
        if (!auth()->check()) return;

        Cart::where('session_id', $this->sessionId)
            ->whereNull('user_id')
            ->update(['user_id' => auth()->id(), 'session_id' => null]);
    }

    protected function getItem(int $cartId): ?Cart
    {
        return Cart::where('id', $cartId)
            ->where(function ($q) {
                if (auth()->check()) {
                    $q->where('user_id', auth()->id());
                } else {
                    $q->where('session_id', $this->sessionId);
                }
            })
            ->first();
    }
}
