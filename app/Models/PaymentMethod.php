<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $fillable = [
        'name',
        'code',
        'logo',
        'account_number',
        'merchant_number',
        'personal_number',
        'instructions',
        'qr_code',
        'min_amount',
        'max_amount',
        'fixed_charge',
        'percent_charge',
        'fixed_discount',
        'percent_discount',
        'is_active',
        'order',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];
}
