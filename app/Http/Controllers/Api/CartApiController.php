<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartApiController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function index(): JsonResponse
    {
        $items = $this->cartService->getItems();
        return response()->json([
            'data' => CartResource::collection($items),
            'subtotal' => $this->cartService->subtotal(),
            'count' => $this->cartService->count(),
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if (!$product->is_in_stock) {
            return response()->json(['message' => 'Product is out of stock.'], 400);
        }

        $this->cartService->add($product, $validated['quantity']);

        return response()->json([
            'message' => 'Product added to cart.',
            'count' => $this->cartService->count(),
        ]);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $validated = $request->validate(['quantity' => 'required|integer|min:1|max:10']);
        $this->cartService->update((int) $id, $validated['quantity']);

        return response()->json(['message' => 'Cart updated.']);
    }

    public function remove($id): JsonResponse
    {
        $this->cartService->remove((int) $id);
        return response()->json(['message' => 'Item removed from cart.']);
    }

    public function clear(): JsonResponse
    {
        $this->cartService->clear();
        return response()->json(['message' => 'Cart cleared.']);
    }
}
