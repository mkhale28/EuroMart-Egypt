<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private CategoryRepositoryInterface $categoryRepository,
        private ProductService $productService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'category', 'brand', 'status', 'sort']);
        $products = $this->productRepository->paginate(12, $filters);
        $categories = $this->categoryRepository->all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = $this->categoryRepository->all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create(
            $request->validated(),
            $request->file('images')
        );

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('images', 'category');
        $categories = $this->categoryRepository->all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->productService->update(
            $product,
            $request->validated(),
            $request->file('images')
        );

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $this->productRepository->delete($product);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function archive(Product $product)
    {
        $this->productRepository->update($product, ['status' => 'archived']);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product archived successfully.');
    }

    public function duplicate(Product $product)
    {
        $clone = $product->replicate();
        $clone->name = $product->name . ' (Copy)';
        $clone->slug = $product->slug . '-copy';
        $clone->sku = $product->sku . '-CPY';
        $clone->save();

        return redirect()
            ->route('admin.products.edit', $clone)
            ->with('success', 'Product duplicated successfully.');
    }

    public function deleteImage(Product $product, $imageId)
    {
        $this->productService->deleteImage($imageId);
        return back()->with('success', 'Image deleted successfully.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,archive,activate',
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ]);

        $products = Product::whereIn('id', $request->products);

        match ($request->action) {
            'delete' => $products->delete(),
            'archive' => $products->update(['status' => 'archived']),
            'activate' => $products->update(['status' => 'active']),
        };

        return back()->with('success', 'Bulk action completed successfully.');
    }
}
