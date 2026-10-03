<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductListResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['category', 'brand', 'min_price', 'max_price', 'sort', 'search', 'featured', 'on_sale']);
        $products = $this->productRepository->paginate($request->per_page ?? 12, $filters);

        return response()->json([
            'data' => ProductListResource::collection($products),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        if ($product->status !== 'active') {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $product->load('category', 'images', 'approvedReviews');
        $product->increment('view_count');

        return response()->json(new ProductResource($product));
    }

    public function featured(): JsonResponse
    {
        $products = $this->productRepository->getFeatured(8);
        return response()->json(ProductListResource::collection($products));
    }

    public function newArrivals(): JsonResponse
    {
        $products = $this->productRepository->getNewArrivals(8);
        return response()->json(ProductListResource::collection($products));
    }

    public function bestSellers(): JsonResponse
    {
        $products = $this->productRepository->getBestSellers(8);
        return response()->json(ProductListResource::collection($products));
    }

    public function related(Product $product): JsonResponse
    {
        $products = $this->productRepository->getRelatedProducts($product, 4);
        return response()->json(ProductListResource::collection($products));
    }
}
