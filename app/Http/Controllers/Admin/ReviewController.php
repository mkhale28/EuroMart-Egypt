<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with('product:id,name')
            ->when($request->status === 'pending', fn($q) => $q->where('is_approved', false))
            ->when($request->status === 'approved', fn($q) => $q->where('is_approved', true))
            ->latest()
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Review approved successfully.');
    }

    public function hide(Review $review)
    {
        $review->update(['is_approved' => false]);

        return back()->with('success', 'Review hidden successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
