<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentlyLog extends Model
{
    protected $fillable = [
        'payment_id',
        'order_number',
        'amount',
        'status',
        'payload',
        'response',
        'ip_address',
    ];

    protected $casts = [
        'payload' => 'array',
        'response' => 'array',
    ];
}
