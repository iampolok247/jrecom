<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Review;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $heroSliders = Banner::where('section', 'hero_slider')->where('status', true)->orderBy('order', 'asc')->get();
        $offerBanners = Banner::where('section', 'offer_banner')->where('status', true)->orderBy('order', 'asc')->get();
        
        $topCategories = Category::where('level', 0)->where('status', true)->orderBy('order', 'asc')->take(8)->get();
        $featuredCategories = Category::where('is_featured', true)->where('status', true)->take(6)->get();

        $latestProducts = Product::with(['category', 'brand', 'images'])->where('is_active', true)->latest()->take(8)->get();
        $featuredProducts = Product::with(['category', 'brand', 'images'])->where('is_active', true)->where('is_featured', true)->take(8)->get();
        $flashSaleProducts = Product::with(['category', 'brand', 'images'])->where('is_active', true)->where('is_flash_sale', true)->take(6)->get();
        $trendingProducts = Product::with(['category', 'brand', 'images'])->where('is_active', true)->where('is_trending', true)->take(8)->get();
        $todayDeals = Product::with(['category', 'brand', 'images'])->where('is_active', true)->where('is_today_deal', true)->take(6)->get();
        $newArrivals = Product::with(['category', 'brand', 'images'])->where('is_active', true)->where('is_new_arrival', true)->take(8)->get();
        $bestSellers = Product::with(['category', 'brand', 'images'])->where('is_active', true)->where('is_best_seller', true)->take(8)->get();

        if ($featuredProducts->isEmpty()) {
            $featuredProducts = $latestProducts;
        }
        if ($newArrivals->isEmpty()) {
            $newArrivals = $latestProducts;
        }
        if ($trendingProducts->isEmpty()) {
            $trendingProducts = $latestProducts;
        }

        $brands = Brand::where('status', true)->where('is_featured', true)->orderBy('order', 'asc')->get();
        $customerReviews = Review::with(['user', 'product'])->where('status', true)->where('rating', '>=', 4)->take(6)->get();

        $sections = HomepageSection::where('is_enabled', true)->orderBy('order', 'asc')->get()->keyBy('key');

        return view('frontend.home', compact(
            'heroSliders',
            'offerBanners',
            'topCategories',
            'featuredCategories',
            'latestProducts',
            'featuredProducts',
            'flashSaleProducts',
            'trendingProducts',
            'todayDeals',
            'newArrivals',
            'bestSellers',
            'brands',
            'customerReviews',
            'sections'
        ));
    }

    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        Subscriber::firstOrCreate(['email' => $request->email]);
        
        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Thank you for subscribing to our newsletter!']);
        }
        return back()->with('success', 'Subscribed successfully!');
    }
}
