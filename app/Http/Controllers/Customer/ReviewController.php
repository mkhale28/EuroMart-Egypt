<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['product_id'] = $product->id;
        $data['user_id'] = auth()->id();

        Review::create($data);

        return back()->with('success', 'Thank you for your review! It will be displayed after approval.');
    }
}
