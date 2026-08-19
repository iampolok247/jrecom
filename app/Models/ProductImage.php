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

        if (Str::startsWith($value, ['http://', 'https://']) && !Str::contains($value, '/storage/')) {
            return $value;
        }

        $relativePath = $value;
        if (Str::contains($relativePath, '/storage/')) {
            $relativePath = Str::after($relativePath, '/storage/');
        } elseif (Str::startsWith($relativePath, 'storage/')) {
            $relativePath = Str::after($relativePath, 'storage/');
        }

        $relativePath = ltrim($relativePath, '/');

        return url('uploads/' . $relativePath);
    }
}
