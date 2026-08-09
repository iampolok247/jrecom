<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RecentlyViewed;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function show($slug)
    {
        $product = Product::with(['category', 'brand', 'images', 'variants.color', 'variants.size', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment views
        $product->increment('total_views');

        // Track Recently Viewed
        $sessionId = session()->getId();
        RecentlyViewed::updateOrCreate(
            [
                'session_id' => $sessionId,
                'product_id' => $product->id,
            ],
            [
                'user_id' => auth()->id(),
                'viewed_at' => now(),
            ]
        );

        // Related Products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        // Frequently Bought Together
        $frequentlyBought = Product::where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(2)
            ->get();

        // Recently Viewed items for user
        $recentlyViewed = RecentlyViewed::with('product')
            ->where('session_id', $sessionId)
            ->where('product_id', '!=', $product->id)
            ->latest('viewed_at')
            ->take(5)
            ->get()
            ->pluck('product')
            ->filter();

        return view('frontend.product-detail', compact('product', 'relatedProducts', 'frequentlyBought', 'recentlyViewed'));
    }

    public function submitReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|min:5',
        ]);

        if (!auth()->check()) {
            return back()->with('error', 'You must be logged in to leave a review.');
        }

        Review::create([
            'product_id' => $id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'review' => $request->review,
            'status' => true,
        ]);

        return back()->with('success', 'Thank you! Your product review has been posted.');
    }
}
