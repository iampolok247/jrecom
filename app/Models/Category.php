<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'icon',
        'parent_id',
        'level',
        'is_featured',
        'status',
        'order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order', 'asc');
    }

    public function subcategories(): HasMany
    {
        return $this->children()->where('level', 1);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
