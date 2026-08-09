<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected $fillable = ['key', 'title', 'subtitle', 'is_enabled', 'order', 'settings'];

    protected $casts = [
        'is_enabled' => 'boolean',
        'settings' => 'array',
    ];
}
