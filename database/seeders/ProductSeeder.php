<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\ShippingClass;
use App\Models\Size;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $electronics = Category::where('name', 'Electronics & Gadgets')->first();
        $fashion = Category::where('name', 'Fashion & Apparel')->first();
        $home = Category::where('name', 'Home & Kitchen Appliances')->first();
        $beauty = Category::where('name', 'Beauty & Personal Care')->first();

        $apple = Brand::where('name', 'Apple')->first();
        $samsung = Brand::where('name', 'Samsung')->first();
        $sony = Brand::where('name', 'Sony')->first();
        $nike = Brand::where('name', 'Nike')->first();

        $unitPc = Unit::first();
        $shipping = ShippingClass::first();
        $colors = Color::all();
        $sizes = Size::all();
        $customer = User::where('role', 'customer')->first();

        $productsData = [
            [
                'name' => 'iPhone 15 Pro Max 256GB - Titanium Natural',
                'category_id' => $electronics->id,
                'brand_id' => $apple->id,
                'regular_price' => 149900.00,
                'sale_price' => 139900.00,
                'stock' => 45,
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_sale' => true,
                'is_today_deal' => true,
                'is_best_seller' => true,
                'short_description' => 'Forged in titanium and featuring the groundbreaking A17 Pro chip, a customizable Action button, and the most powerful iPhone camera system ever.',
                'long_description' => '<p>iPhone 15 Pro Max is the first iPhone to feature an aerospace-grade titanium design, using the same alloy that spacecraft use for missions to Mars. Titanium has one of the best strength-to-weight ratios of any metal, making these our lightest Pro models ever. You’ll notice the difference the moment you pick one up.</p>',
                'specification' => [
                    'Display' => '6.7-inch Super Retina XDR OLED',
                    'Processor' => 'Apple A17 Pro (3nm)',
                    'Main Camera' => '48MP Main + 12MP Telephoto 5x + 12MP Ultrawide',
                    'Battery' => '4422 mAh with 20W fast charging',
                    'OS' => 'iOS 17'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&q=80',
                    'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=800&q=80'
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra 5G AI Phone',
                'category_id' => $electronics->id,
                'brand_id' => $samsung->id,
                'regular_price' => 135000.00,
                'sale_price' => 124900.00,
                'stock' => 30,
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_sale' => true,
                'is_today_deal' => false,
                'is_best_seller' => true,
                'short_description' => 'Unleash new ways to create, connect and collaborate with Galaxy AI. 200MP camera, built-in S Pen, and Snapdragon 8 Gen 3 chipset.',
                'long_description' => '<p>Welcome to the era of mobile AI. With Galaxy S24 Ultra in your hands, you can unleash whole new levels of creativity, productivity and possibility — starting with the most important device in your life. Your smartphone.</p>',
                'specification' => [
                    'Display' => '6.8-inch Dynamic AMOLED 2X 120Hz',
                    'Camera' => '200MP + 50MP + 12MP + 10MP',
                    'Stylus' => 'Integrated S-Pen included'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=800&q=80',
                    'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=800&q=80'
                ]
            ],
            [
                'name' => 'Sony WH-1000XM5 Wireless Noise Canceling Headphones',
                'category_id' => $electronics->id,
                'brand_id' => $sony->id,
                'regular_price' => 38000.00,
                'sale_price' => 32900.00,
                'stock' => 60,
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_sale' => false,
                'is_today_deal' => true,
                'is_best_seller' => true,
                'short_description' => 'Industry-leading noise canceling with two processors and 8 microphones for extraordinary sound quality and crystal clear hands-free calls.',
                'long_description' => '<p>The WH-1000XM5 headphones rewrite the rules for distraction-free listening. 30-hour battery life, ultra-comfortable design, multipoint connection.</p>',
                'specification' => [
                    'Driver Unit' => '30mm',
                    'Battery Life' => 'Up to 30 Hours',
                    'Connectivity' => 'Bluetooth 5.2, 3.5mm Aux'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800&q=80',
                    'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&q=80'
                ]
            ],
            [
                'name' => 'Nike Air Zoom Pegasus 40 Running Shoes',
                'category_id' => $fashion->id,
                'brand_id' => $nike->id,
                'regular_price' => 14500.00,
                'sale_price' => 11900.00,
                'stock' => 80,
                'is_featured' => true,
                'is_trending' => false,
                'is_flash_sale' => true,
                'is_today_deal' => true,
                'is_best_seller' => true,
                'short_description' => 'A springy ride for any run, the Peg\'s familiar, just-for-you feel returns to help you accomplish your goals with dual Zoom Air units.',
                'long_description' => '<p>Pegasus 40 represents the history and future of Nike Running. Whether you’re a marathon runner or casual jogger, experience supreme comfort.</p>',
                'specification' => [
                    'Material' => 'Engineered Mesh Upper',
                    'Cushioning' => 'Nike React Foam + Zoom Air',
                    'Weight' => '288g'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80',
                    'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=800&q=80'
                ]
            ],
            [
                'name' => 'Premium Smart Digital Espresso Coffee Maker 15-Bar',
                'category_id' => $home->id,
                'regular_price' => 22000.00,
                'sale_price' => 17800.00,
                'stock' => 25,
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_sale' => false,
                'is_today_deal' => false,
                'is_best_seller' => false,
                'short_description' => 'Brew cafe-quality espresso, cappuccino and latte at home with 15-bar Italian pump pressure and milk frother wand.',
                'long_description' => '<p>Elevate your morning routine with rich, authentic Italian espresso crafted right in your kitchen.</p>',
                'specification' => [
                    'Pump Pressure' => '15 Bar',
                    'Water Tank' => '1.5 Liter',
                    'Power' => '1050W'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1517668808822-9e428824603b?w=800&q=80',
                    'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?w=800&q=80'
                ]
            ],
            [
                'name' => 'Luxury Botanical Glow Face Serum & Elixir (50ml)',
                'category_id' => $beauty->id,
                'regular_price' => 4500.00,
                'sale_price' => 3600.00,
                'stock' => 120,
                'is_featured' => true,
                'is_trending' => true,
                'is_flash_sale' => true,
                'is_today_deal' => true,
                'is_best_seller' => true,
                'short_description' => 'Infused with Hyaluronic Acid, Niacinamide, and Vitamin C for deeply hydrated, luminous skin glowing with youthful radiance.',
                'long_description' => '<p>Dermatologist-tested fast-absorbing serum that repairs skin barrier and reduces fine lines in 7 days.</p>',
                'specification' => [
                    'Volume' => '50ml',
                    'Skin Type' => 'All Skin Types',
                    'Key Ingredient' => 'Hyaluronic Acid 2% + Vit C'
                ],
                'images' => [
                    'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?w=800&q=80',
                    'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=800&q=80'
                ]
            ]
        ];

        foreach ($productsData as $p) {
            $product = Product::create([
                'category_id' => $p['category_id'],
                'brand_id' => $p['brand_id'] ?? null,
                'unit_id' => $unitPc->id,
                'shipping_class_id' => $shipping->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'sku' => 'SKU-' . strtoupper(Str::random(6)),
                'barcode' => '880' . rand(100000000, 999999999),
                'stock' => $p['stock'],
                'purchase_price' => $p['sale_price'] * 0.75,
                'regular_price' => $p['regular_price'],
                'sale_price' => $p['sale_price'],
                'discount' => $p['regular_price'] - $p['sale_price'],
                'discount_type' => 'flat',
                'short_description' => $p['short_description'],
                'long_description' => $p['long_description'],
                'specification' => $p['specification'],
                'return_policy' => '7 days easy replacement & money back return warranty.',
                'warranty' => '1 Year Official Brand Warranty.',
                'seo_title' => $p['name'],
                'seo_description' => $p['short_description'],
                'seo_keywords' => 'ecommerce, online shop, buy ' . $p['name'],
                'is_featured' => $p['is_featured'],
                'is_trending' => $p['is_trending'],
                'is_flash_sale' => $p['is_flash_sale'],
                'is_today_deal' => $p['is_today_deal'],
                'is_best_seller' => $p['is_best_seller'],
                'is_active' => true,
                'total_views' => rand(120, 1500),
                'sales_count' => rand(15, 250),
            ]);

            foreach ($p['images'] as $idx => $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $img,
                    'is_primary' => ($idx === 0),
                    'order' => $idx,
                ]);
            }

            // Create Variants
            foreach ($colors->take(2) as $c) {
                foreach ($sizes->take(2) as $s) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id' => $c->id,
                        'size_id' => $s->id,
                        'sku' => $product->sku . '-' . $c->code . '-' . $s->code,
                        'stock' => 15,
                        'price_adjustment' => rand(0, 500),
                    ]);
                }
            }

            // Create Reviews
            if ($customer) {
                Review::create([
                    'product_id' => $product->id,
                    'user_id' => $customer->id,
                    'rating' => 5,
                    'review' => 'Absolutely incredible product! Fast delivery, original authentic quality and fantastic packaging.',
                    'status' => true,
                ]);
            }
        }
    }
}
