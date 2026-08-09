<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Categories (supports tree structure: parent, sub, child)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->integer('level')->default(0); // 0=Root, 1=Sub, 2=Child
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 2. Brands
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 3. Units
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->timestamps();
        });

        // 4. Colors
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code'); // Hex color
            $table->timestamps();
        });

        // 5. Sizes
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->timestamps();
        });

        // 6. Shipping Classes
        Schema::create('shipping_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('cost', 10, 2)->default(0);
            $table->integer('estimated_days')->default(3);
            $table->timestamps();
        });

        // 7. Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('subcategory_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('child_category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('shipping_class_id')->nullable()->constrained('shipping_classes')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->string('barcode')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('regular_price', 10, 2);
            $table->decimal('sale_price', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->enum('discount_type', ['flat', 'percent'])->default('flat');
            $table->string('weight')->nullable();
            $table->string('dimensions')->nullable();
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->string('video_url')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('long_description')->nullable();
            $table->json('specification')->nullable();
            $table->text('return_policy')->nullable();
            $table->text('warranty')->nullable();
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->boolean('is_flash_sale')->default(false);
            $table->boolean('is_today_deal')->default(false);
            $table->boolean('is_new_arrival')->default(true);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('total_views')->default(0);
            $table->integer('sales_count')->default(0);
            $table->timestamps();
        });

        // 8. Product Gallery Images
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->string('image');
            $table->boolean('is_primary')->default(false);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 9. Product Variants
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('color_id')->nullable()->constrained('colors')->nullOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('sizes')->nullOnDelete();
            $table->string('sku')->nullable();
            $table->integer('stock')->default(0);
            $table->decimal('price_adjustment', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 10. Coupons
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percent'])->default('fixed');
            $table->decimal('value', 10, 2);
            $table->decimal('min_spend', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 11. Payment Methods (Paymently.io, bKash, Nagad, Rocket, COD, etc.)
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique(); // paymently, bkash, nagad, rocket, bank_transfer, cod
            $table->string('logo')->nullable();
            $table->string('account_number')->nullable();
            $table->string('merchant_number')->nullable();
            $table->string('personal_number')->nullable();
            $table->text('instructions')->nullable();
            $table->string('qr_code')->nullable();
            $table->decimal('min_amount', 10, 2)->default(0);
            $table->decimal('max_amount', 10, 2)->nullable();
            $table->decimal('fixed_charge', 10, 2)->default(0);
            $table->decimal('percent_charge', 5, 2)->default(0);
            $table->decimal('fixed_discount', 10, 2)->default(0);
            $table->decimal('percent_discount', 5, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        // 12. Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('billing_name');
            $table->string('billing_email');
            $table->string('billing_phone');
            $table->text('billing_address');
            $table->string('billing_city');
            $table->string('billing_state')->nullable();
            $table->string('billing_zip')->nullable();
            $table->string('billing_country')->default('Bangladesh');
            
            $table->string('shipping_name')->nullable();
            $table->string('shipping_email')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_zip')->nullable();
            $table->string('shipping_country')->nullable();
            
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('coupon_code')->nullable();
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('payment_method_name')->default('Cash On Delivery');
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->enum('order_status', ['pending', 'confirmed', 'processing', 'packing', 'shipped', 'delivered', 'cancelled', 'returned', 'refunded'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->json('payment_details')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 13. Order Items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->json('variant_info')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });

        // 14. Order Timelines
        Schema::create('order_timelines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('status');
            $table->text('comment')->nullable();
            $table->string('created_by')->default('System');
            $table->timestamps();
        });

        // 15. Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->integer('rating')->default(5);
            $table->text('review')->nullable();
            $table->json('images')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 16. Wishlists
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();
        });

        // 17. Compares
        Schema::create('compares', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();
        });

        // 18. Recently Viewed
        Schema::create('recently_viewed', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();
        });

        // 19. Site Settings
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // 20. Homepage Sections
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->integer('order')->default(0);
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        // 21. Banners
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('image');
            $table->string('link')->nullable();
            $table->string('button_text')->nullable();
            $table->enum('section', ['hero_slider', 'top_banner', 'offer_banner', 'ad_banner', 'popup'])->default('hero_slider');
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 22. Blogs
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable();
            $table->longText('content');
            $table->string('category')->default('E-Commerce');
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('views')->default(0);
            $table->boolean('status')->default(true);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        // 23. Testimonials
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('avatar')->nullable();
            $table->integer('rating')->default(5);
            $table->text('text');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 24. FAQs
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->default('General');
            $table->boolean('status')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        // 25. Subscribers
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        // 26. Paymently Logs
        Schema::create('paymently_logs', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id')->nullable();
            $table->string('order_number')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('status')->default('initiated');
            $table->json('payload')->nullable();
            $table->json('response')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });

        // 27. Activity Logs
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('paymently_logs');
        Schema::dropIfExists('subscribers');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('homepage_sections');
        Schema::dropIfExists('site_settings');
        Schema::dropIfExists('recently_viewed');
        Schema::dropIfExists('compares');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('order_timelines');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('shipping_classes');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('colors');
        Schema::dropIfExists('units');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
    }
};
