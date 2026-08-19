<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image', 'is_primary', 'order'];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageAttribute($value): string
    {
        if (empty($value)) {
            return 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&q=80';
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            if (Str::contains($value, '/storage/')) {
                $relativePath = 'storage/' . Str::after($value, '/storage/');
                return asset($relativePath);
            }
            return $value;
        }

        if (Str::startsWith($value, 'storage/')) {
            return asset($value);
        }

        if (Str::startsWith($value, 'products/')) {
            return asset('storage/' . $value);
        }

        return asset($value);
    }
}
