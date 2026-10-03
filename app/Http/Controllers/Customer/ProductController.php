<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private WhatsAppService $whatsAppService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'brand', 'min_price', 'max_price', 'sort', 'on_sale']);
        $products = $this->productRepository->paginate(12, $filters);
        $categories = $this->categoryRepository->getWithProductCount();
        $brands = Product::active()->select('brand')->distinct()->whereNotNull('brand')->pluck('brand');

        return view('customer.products.index', compact('products', 'categories', 'brands'));
    }

    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }

        $product->increment('view_count');
        $product->load('category', 'images', 'approvedReviews.user');

        $relatedProducts = $this->productRepository->getRelatedProducts($product, 4);
        $whatsappLink = $this->whatsAppService->getWhatsAppLink(
            $this->whatsAppService->generateOrderMessage($product->name, $product->display_price)
        );

        $maxQuantity = min($product->stock, 10);

        return view('customer.products.show', compact('product', 'relatedProducts', 'whatsappLink', 'maxQuantity'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $products = $this->productRepository->search($query, 12);
        $categories = $this->categoryRepository->getWithProductCount();

        return view('customer.products.index', compact('products', 'categories', 'query'));
    }

    public function byCategory(Request $request, $slug)
    {
        $filters = $request->only(['search', 'brand', 'min_price', 'max_price', 'sort']);
        $filters['category'] = $slug;
        $products = $this->productRepository->paginate(12, $filters);
        $categories = $this->categoryRepository->getWithProductCount();
        $brands = Product::active()->select('brand')->distinct()->whereNotNull('brand')->pluck('brand');

        return view('customer.products.index', compact('products', 'categories', 'brands'));
    }
}
