<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_spend',
        'max_discount',
        'start_date',
        'expiry_date',
        'usage_limit',
        'used_count',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'expiry_date' => 'date',
        'status' => 'boolean',
    ];

    public function isValidForAmount(float $subtotal): bool
    {
        if (!$this->status) return false;
        if ($this->expiry_date && $this->expiry_date->isPast()) return false;
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) return false;
        if ($subtotal < $this->min_spend) return false;
        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->max_discount && $discount > $this->max_discount) {
                return (float) $this->max_discount;
            }
            return (float) $discount;
        }
        return (float) min($this->value, $subtotal);
    }
}
