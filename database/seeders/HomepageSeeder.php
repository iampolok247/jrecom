<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        // Homepage Sections configuration
        $sections = [
            ['key' => 'hero_slider', 'title' => 'Hero Banner Slider', 'order' => 1],
            ['key' => 'top_categories', 'title' => 'Top Categories Grid', 'order' => 2],
            ['key' => 'flash_sale', 'title' => 'Flash Sale Limited Countdown', 'order' => 3],
            ['key' => 'featured_products', 'title' => 'Featured Products', 'order' => 4],
            ['key' => 'offer_banner', 'title' => 'Special Promo Banners', 'order' => 5],
            ['key' => 'trending_products', 'title' => 'Trending Products', 'order' => 6],
            ['key' => 'today_deals', 'title' => 'Today\'s Super Deals', 'order' => 7],
            ['key' => 'popular_brands', 'title' => 'Popular Brands Showcase', 'order' => 8],
            ['key' => 'new_arrivals', 'title' => 'New Arrivals', 'order' => 9],
            ['key' => 'best_sellers', 'title' => 'Best Sellers', 'order' => 10],
            ['key' => 'newsletter', 'title' => 'Newsletter Subscription Banner', 'order' => 11],
        ];

        foreach ($sections as $s) {
            HomepageSection::updateOrCreate(
                ['key' => $s['key']],
                [
                    'title' => $s['title'],
                    'is_enabled' => true,
                    'order' => $s['order'],
                ]
            );
        }

        // Banners
        $banners = [
            [
                'title' => 'Next-Gen Smartphone Era',
                'subtitle' => 'Save up to 30% off on flagship titanium series',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=1400&q=80',
                'link' => '/shop?category=electronics-gadgets',
                'button_text' => 'Shop Flagships',
                'section' => 'hero_slider',
                'order' => 1,
            ],
            [
                'title' => 'Luxury Audio & Noise Canceling',
                'subtitle' => 'Immerse in pure high-res acoustic precision',
                'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=1400&q=80',
                'link' => '/shop?category=electronics-gadgets',
                'button_text' => 'Explore Audio',
                'section' => 'hero_slider',
                'order' => 2,
            ],
            [
                'title' => 'Summer Fashion Collection 2026',
                'subtitle' => 'Discover trending footwear, activewear and apparel',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=1400&q=80',
                'link' => '/shop?category=fashion-apparel',
                'button_text' => 'Explore Fashion',
                'section' => 'hero_slider',
                'order' => 3,
            ],
            [
                'title' => 'Mega Flash Sale Discount',
                'subtitle' => 'Instant BDT 5,000 Off with Coupon: JRECOM2026',
                'image' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1200&q=80',
                'link' => '/shop',
                'button_text' => 'Claim Voucher',
                'section' => 'offer_banner',
                'order' => 1,
            ]
        ];

        foreach ($banners as $b) {
            Banner::create($b);
        }
    }
}
