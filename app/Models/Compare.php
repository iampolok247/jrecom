<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Compare extends Model
{
    protected $fillable = ['session_id', 'user_id', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
