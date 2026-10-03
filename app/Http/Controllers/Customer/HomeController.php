<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use App\Repositories\Contracts\ProductRepositoryInterface;

class HomeController extends Controller
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function index()
    {
        $featuredProducts = $this->productRepository->getFeatured(8);
        $newArrivals = $this->productRepository->getNewArrivals(8);
        $bestSellers = $this->productRepository->getBestSellers(8);
        $categories = Category::active()->ordered()->withCount('activeProducts')->get();
        $testimonials = Testimonial::active()->get();
        $onSaleProducts = Product::active()->onSale()->inStock()->latest()->take(4)->get();

        return view('customer.home', compact(
            'featuredProducts',
            'newArrivals',
            'bestSellers',
            'categories',
            'testimonials',
            'onSaleProducts'
        ));
    }
}
