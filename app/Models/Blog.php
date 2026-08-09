<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title', 'slug', 'image', 'content', 'category', 'author_id', 'views', 'status', 'meta_title', 'meta_description'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
