<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'JR-Ecom',
            'site_title' => 'JR-Ecom | Premium Multi-Category Shopping Destination',
            'site_tagline' => 'Discover Next-Gen Products & Daily Deals',
            'site_logo' => 'https://img.icons8.com/gradient/96/shopping-bag.png',
            'site_dark_logo' => 'https://img.icons8.com/gradient/96/shopping-bag.png',
            'site_footer_logo' => 'https://img.icons8.com/gradient/96/shopping-bag.png',
            'site_favicon' => 'https://img.icons8.com/gradient/32/shopping-bag.png',
            'primary_color' => '#4f46e5',
            'secondary_color' => '#06b6d4',
            'accent_color' => '#f59e0b',
            'background_color' => '#f9fafb',
            'typography' => 'Inter, sans-serif',
            'border_radius' => '12px',
            'site_currency' => 'BDT',
            'currency_symbol' => '৳',
            'support_phone' => '+880 1700 000 000',
            'whatsapp_phone' => '+880 1800 000 000',
            'support_email' => 'support@jrecom.com',
            'office_address' => 'Level 8, Prime Tower, Gulshan 2, Dhaka-1212, Bangladesh',
            'facebook_url' => 'https://facebook.com',
            'instagram_url' => 'https://instagram.com',
            'youtube_url' => 'https://youtube.com',
            'twitter_url' => 'https://twitter.com',
            'linkedin_url' => 'https://linkedin.com',
            'footer_copyright' => '© 2026 Ravelis. All Rights Reserved. Designed for Excellence.',
            'footer_description' => 'Ravelis is a premium lifestyle e-commerce brand dedicated to bringing elegance, quality and timeless style to your everyday life. Discover carefully selected products designed to add sophistication and value to every moment.',
            'seo_meta_title' => 'JR-Ecom - Online Shopping in Bangladesh',
            'seo_meta_description' => 'Shop electronics, fashion, gadgets, home appliances, and cosmetics at unbeatable prices with cash on delivery and Paymently.io.',
            'seo_meta_keywords' => 'ecommerce, online shop, bangladesh shopping, electronics, fashion, deals',
            'paymently_base_url' => 'https://api.paymently.io/v1',
            'paymently_api_key' => 'demo_api_key_jr_ecom',
            'paymently_secret_key' => 'demo_secret_key_jr_ecom',
            'paymently_environment' => 'sandbox',
            'paymently_enabled' => '1',
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::setKey($key, $value);
        }
    }
}
