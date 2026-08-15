<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ShippingClass;
use App\Models\Size;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'variants.color', 'variants.size']);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
        }
        $products = $query->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('level', 0)->with('children')->get();
        $brands = Brand::where('status', true)->get();
        $units = Unit::all();
        $colors = Color::all();
        $sizes = Size::all();
        $shippingClasses = ShippingClass::all();

        return view('admin.products.create', compact('categories', 'brands', 'units', 'colors', 'sizes', 'shippingClasses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'shipping_class_id' => 'nullable|exists:shipping_classes,id',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku',
            'primary_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
        ]);

        $product = Product::create([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->filled('subcategory_id') ? $request->subcategory_id : null,
            'brand_id' => $request->filled('brand_id') ? $request->brand_id : null,
            'unit_id' => $request->filled('unit_id') ? $request->unit_id : null,
            'shipping_class_id' => $request->filled('shipping_class_id') ? $request->shipping_class_id : null,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . Str::random(4),
            'sku' => $request->sku,
            'barcode' => $request->filled('barcode') ? $request->barcode : null,
            'stock' => $request->stock,
            'purchase_price' => $request->filled('purchase_price') ? $request->purchase_price : 0,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->filled('sale_price') ? $request->sale_price : null,
            'discount' => ($request->filled('sale_price') && $request->sale_price < $request->regular_price) ? ($request->regular_price - $request->sale_price) : 0,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'specification' => $request->specification ? json_decode($request->specification, true) : null,
            'seo_title' => $request->filled('seo_title') ? $request->seo_title : $request->name,
            'seo_description' => $request->seo_description,
            'seo_keywords' => $request->seo_keywords,
            'is_featured' => $request->boolean('is_featured'),
            'is_trending' => $request->boolean('is_trending'),
            'is_flash_sale' => $request->boolean('is_flash_sale'),
            'is_today_deal' => $request->boolean('is_today_deal'),
            'is_new_arrival' => $request->boolean('is_new_arrival'),
            'is_best_seller' => $request->boolean('is_best_seller'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        // Process Primary Image File or URL
        $primaryImgPath = 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800&q=80';
        if ($request->hasFile('primary_image_file')) {
            $file = $request->file('primary_image_file');
            $fileName = time() . '_primary_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $fileName, 'public');
            $primaryImgPath = asset('storage/' . $path);
        } elseif ($request->filled('primary_image_url')) {
            $primaryImgPath = $request->primary_image_url;
        }

        ProductImage::create([
            'product_id' => $product->id,
            'image' => $primaryImgPath,
            'is_primary' => true,
            'order' => 0,
        ]);

        // Process Gallery File Uploads (up to 6 images)
        if ($request->hasFile('gallery_images')) {
            $galleryFiles = array_slice($request->file('gallery_images'), 0, 6);
            foreach ($galleryFiles as $idx => $gFile) {
                if ($gFile && $gFile->isValid()) {
                    $gName = time() . '_gal_' . $idx . '_' . Str::random(4) . '.' . $gFile->getClientOriginalExtension();
                    $gPath = $gFile->storeAs('products', $gName, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => asset('storage/' . $gPath),
                        'is_primary' => false,
                        'order' => $idx + 1,
                    ]);
                }
            }
        }

        // Process Gallery Image URLs if provided
        if ($request->filled('gallery_urls')) {
            $urls = array_filter(array_map('trim', explode("\n", $request->gallery_urls)));
            foreach (array_slice($urls, 0, 6) as $idx => $url) {
                if (!empty($url)) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $url,
                        'is_primary' => false,
                        'order' => $idx + 10,
                    ]);
                }
            }
        }

        // Save Color and Size Variant Combinations
        $this->syncVariants($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully with image uploads & variants!');
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'variants.color', 'variants.size'])->findOrFail($id);
        $categories = Category::where('level', 0)->with('children')->get();
        $brands = Brand::where('status', true)->get();
        $units = Unit::all();
        $colors = Color::all();
        $sizes = Size::all();
        $shippingClasses = ShippingClass::all();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'units', 'colors', 'sizes', 'shippingClasses'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'shipping_class_id' => 'nullable|exists:shipping_classes,id',
            'regular_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'primary_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:4096',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'subcategory_id' => $request->filled('subcategory_id') ? $request->subcategory_id : null,
            'brand_id' => $request->filled('brand_id') ? $request->brand_id : null,
            'unit_id' => $request->filled('unit_id') ? $request->unit_id : null,
            'shipping_class_id' => $request->filled('shipping_class_id') ? $request->shipping_class_id : null,
            'name' => $request->name,
            'sku' => $request->sku,
            'stock' => $request->stock,
            'purchase_price' => $request->filled('purchase_price') ? $request->purchase_price : 0,
            'regular_price' => $request->regular_price,
            'sale_price' => $request->filled('sale_price') ? $request->sale_price : null,
            'discount' => ($request->filled('sale_price') && $request->sale_price < $request->regular_price) ? ($request->regular_price - $request->sale_price) : 0,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'is_featured' => $request->boolean('is_featured'),
            'is_trending' => $request->boolean('is_trending'),
            'is_flash_sale' => $request->boolean('is_flash_sale'),
            'is_today_deal' => $request->boolean('is_today_deal'),
            'is_active' => $request->boolean('is_active'),
        ]);

        // Process Primary Image Upload if provided
        if ($request->hasFile('primary_image_file')) {
            $file = $request->file('primary_image_file');
            $fileName = time() . '_primary_' . Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $fileName, 'public');
            
            // Update or create primary image
            ProductImage::where('product_id', $product->id)->where('is_primary', true)->delete();
            ProductImage::create([
                'product_id' => $product->id,
                'image' => asset('storage/' . $path),
                'is_primary' => true,
                'order' => 0,
            ]);
        } elseif ($request->filled('primary_image_url')) {
            ProductImage::where('product_id', $product->id)->where('is_primary', true)->delete();
            ProductImage::create([
                'product_id' => $product->id,
                'image' => $request->primary_image_url,
                'is_primary' => true,
                'order' => 0,
            ]);
        }

        // Upload additional gallery images (up to 6)
        if ($request->hasFile('gallery_images')) {
            $galleryFiles = array_slice($request->file('gallery_images'), 0, 6);
            foreach ($galleryFiles as $idx => $gFile) {
                if ($gFile && $gFile->isValid()) {
                    $gName = time() . '_gal_' . $idx . '_' . Str::random(4) . '.' . $gFile->getClientOriginalExtension();
                    $gPath = $gFile->storeAs('products', $gName, 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => asset('storage/' . $gPath),
                        'is_primary' => false,
                        'order' => $idx + 1,
                    ]);
                }
            }
        }

        // Sync Color & Size Variants
        $this->syncVariants($product, $request);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    protected function syncVariants(Product $product, Request $request): void
    {
        $product->variants()->delete();
        $colors = $request->input('colors', []);
        $sizes = $request->input('sizes', []);

        // Create new inline custom color if admin entered new color details
        if ($request->filled('new_color_name') && $request->filled('new_color_code')) {
            $newColor = Color::create([
                'name' => $request->new_color_name,
                'code' => Str::startsWith($request->new_color_code, '#') ? $request->new_color_code : ('#' . $request->new_color_code),
            ]);
            $colors[] = $newColor->id;
        }

        // Create new inline custom size / storage option if admin entered new size details
        if ($request->filled('new_size_name') && $request->filled('new_size_code')) {
            $newSize = Size::create([
                'name' => $request->new_size_name,
                'code' => $request->new_size_code,
            ]);
            $sizes[] = $newSize->id;
        }

        if (!empty($colors) && !empty($sizes)) {
            foreach ($colors as $cId) {
                foreach ($sizes as $sId) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id' => $cId,
                        'size_id' => $sId,
                        'sku' => $product->sku . '-C' . $cId . '-S' . $sId,
                        'stock' => 20,
                        'price_adjustment' => 0,
                    ]);
                }
            }
        } elseif (!empty($colors)) {
            foreach ($colors as $cId) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => $cId,
                    'size_id' => null,
                    'sku' => $product->sku . '-C' . $cId,
                    'stock' => 20,
                    'price_adjustment' => 0,
                ]);
            }
        } elseif (!empty($sizes)) {
            foreach ($sizes as $sId) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'color_id' => null,
                    'size_id' => $sId,
                    'sku' => $product->sku . '-S' . $sId,
                    'stock' => 20,
                    'price_adjustment' => 0,
                ]);
            }
        }
    }
}
