<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderTimeline extends Model
{
    protected $fillable = ['order_id', 'status', 'comment', 'created_by'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
