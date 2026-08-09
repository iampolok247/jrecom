<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    protected $fillable = ['name', 'designation', 'avatar', 'rating', 'text', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
