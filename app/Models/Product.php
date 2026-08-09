<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'subcategory_id',
        'child_category_id',
        'brand_id',
        'unit_id',
        'shipping_class_id',
        'name',
        'slug',
        'sku',
        'barcode',
        'stock',
        'purchase_price',
        'regular_price',
        'sale_price',
        'discount',
        'discount_type',
        'weight',
        'dimensions',
        'tax_percent',
        'video_url',
        'short_description',
        'long_description',
        'specification',
        'return_policy',
        'warranty',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'is_featured',
        'is_trending',
        'is_flash_sale',
        'is_today_deal',
        'is_new_arrival',
        'is_best_seller',
        'is_active',
        'total_views',
        'sales_count',
    ];

    protected $casts = [
        'specification' => 'array',
        'is_featured' => 'boolean',
        'is_trending' => 'boolean',
        'is_flash_sale' => 'boolean',
        'is_today_deal' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function childCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'child_category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function shippingClass(): BelongsTo
    {
        return $this->belongsTo(ShippingClass::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order', 'asc');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', true);
    }

    public function getEffectivePriceAttribute(): float
    {
        if ($this->sale_price && $this->sale_price > 0 && $this->sale_price < $this->regular_price) {
            return (float) $this->sale_price;
        }
        return (float) $this->regular_price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if ($this->regular_price <= 0 || !$this->sale_price || $this->sale_price >= $this->regular_price) {
            return 0;
        }
        return (int) round((($this->regular_price - $this->sale_price) / $this->regular_price) * 100);
    }

    public function getAverageRatingAttribute(): float
    {
        return (float) round($this->reviews()->avg('rating') ?? 5, 1);
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();
        if ($primary && $primary->image) {
            if (str_starts_with($primary->image, 'http://') || str_starts_with($primary->image, 'https://')) {
                return $primary->image;
            }
            return asset($primary->image);
        }
        return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80';
    }
}
