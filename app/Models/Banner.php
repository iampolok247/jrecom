<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = ['title', 'subtitle', 'image', 'link', 'button_text', 'section', 'status', 'order'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
