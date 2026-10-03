<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService
    ) {}

    public function index()
    {
        $cartItems = $this->cartService->getItems();
        $subtotal = $this->cartService->subtotal();
        $cartCount = $this->cartService->count();

        return view('customer.cart.index', compact('cartItems', 'subtotal', 'cartCount'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_in_stock) {
            return back()->with('error', 'This product is out of stock.');
        }

        if ($request->quantity > $product->stock) {
            return back()->with('error', "Only {$product->stock} items available in stock.");
        }

        $this->cartService->add($product, $request->quantity);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Product added to cart successfully!');
    }

    public function update(Request $request, $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $this->cartService->update((int) $cartId, $request->quantity);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove($cartId)
    {
        $this->cartService->remove((int) $cartId);

        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        $this->cartService->clear();

        return redirect()->route('cart.index')->with('success', 'Cart cleared.');
    }

    public function count()
    {
        return response()->json(['count' => $this->cartService->count()]);
    }
}
