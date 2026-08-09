<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images'])->where('is_active', true);

        // Search Keyword
        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('sku', 'like', "%{$keyword}%")
                  ->orWhere('short_description', 'like', "%{$keyword}%");
            });
        }

        // Category Filter
        if ($request->filled('category')) {
            $catSlug = $request->category;
            $category = Category::where('slug', $catSlug)->first();
            if ($category) {
                if ($category->level === 0) {
                    $catIds = Category::where('id', $category->id)->orWhere('parent_id', $category->id)->pluck('id');
                    $query->whereIn('category_id', $catIds);
                } else {
                    $query->where('category_id', $category->id);
                }
            }
        }

        // Brand Filter
        if ($request->filled('brand')) {
            $brandSlugs = is_array($request->brand) ? $request->brand : [$request->brand];
            $brandIds = Brand::whereIn('slug', $brandSlugs)->pluck('id');
            $query->whereIn('brand_id', $brandIds);
        }

        // Price Filter
        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '>=', $request->min_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('sale_price')->where('regular_price', '>=', $request->min_price);
                  });
            });
        }
        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('sale_price', '<=', $request->max_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('sale_price')->where('regular_price', '<=', $request->max_price);
                  });
            });
        }

        // Sorting
        switch ($request->sort) {
            case 'price_low_high':
                $query->orderByRaw('COALESCE(NULLIF(sale_price, 0), regular_price) ASC');
                break;
            case 'price_high_low':
                $query->orderByRaw('COALESCE(NULLIF(sale_price, 0), regular_price) DESC');
                break;
            case 'best_selling':
                $query->orderBy('sales_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('level', 0)->with('children')->where('status', true)->get();
        $brands = Brand::where('status', true)->get();
        $maxPriceInDb = Product::max('regular_price') ?? 150000;

        return view('frontend.shop', compact('products', 'categories', 'brands', 'maxPriceInDb'));
    }

    public function searchSuggestions(Request $request)
    {
        $q = trim($request->get('query', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $products = Product::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('sku', 'like', "%{$q}%");
            })
            ->take(6)
            ->get(['id', 'name', 'slug', 'regular_price', 'sale_price']);

        $results = [];
        foreach ($products as $p) {
            $results[] = [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'price' => '৳' . number_format($p->effective_price, 2),
                'url' => route('product.detail', $p->slug),
                'image' => $p->primary_image_url,
            ];
        }

        return response()->json($results);
    }
}
