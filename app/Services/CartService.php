<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'jr_ecom_cart';
    protected string $couponKey = 'jr_ecom_coupon';

    public function getCart(): array
    {
        return Session::get($this->sessionKey, []);
    }

    public function add(Product $product, int $quantity = 1, array $variant = []): array
    {
        $cart = $this->getCart();
        $itemKey = $product->id . ($variant ? '_' . md5(json_encode($variant)) : '');

        $price = $product->effective_price;
        if (!empty($variant['price_adjustment'])) {
            $price += (float) $variant['price_adjustment'];
        }

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] += $quantity;
            $cart[$itemKey]['total'] = $cart[$itemKey]['quantity'] * $cart[$itemKey]['price'];
        } else {
            $cart[$itemKey] = [
                'key' => $itemKey,
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $variant['sku'] ?? $product->sku,
                'image' => $product->primary_image_url,
                'price' => $price,
                'quantity' => $quantity,
                'variant' => $variant,
                'total' => $price * $quantity,
            ];
        }

        Session::put($this->sessionKey, $cart);
        return $cart;
    }

    public function update(string $key, int $quantity): array
    {
        $cart = $this->getCart();
        if (isset($cart[$key])) {
            if ($quantity <= 0) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $quantity;
                $cart[$key]['total'] = $cart[$key]['price'] * $quantity;
            }
            Session::put($this->sessionKey, $cart);
        }
        return $cart;
    }

    public function remove(string $key): array
    {
        $cart = $this->getCart();
        if (isset($cart[$key])) {
            unset($cart[$key]);
            Session::put($this->sessionKey, $cart);
        }
        return $cart;
    }

    public function clear(): void
    {
        Session::forget($this->sessionKey);
        Session::forget($this->couponKey);
    }

    public function getSubtotal(): float
    {
        $subtotal = 0;
        foreach ($this->getCart() as $item) {
            $subtotal += $item['total'];
        }
        return (float) $subtotal;
    }

    public function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', $code)->where('status', true)->first();
        if (!$coupon) {
            return ['success' => false, 'message' => 'Invalid or expired coupon code.'];
        }

        $subtotal = $this->getSubtotal();
        if (!$coupon->isValidForAmount($subtotal)) {
            return ['success' => false, 'message' => 'Coupon requirements not met (min spend BDT ' . number_format($coupon->min_spend, 2) . ').'];
        }

        Session::put($this->couponKey, [
            'code' => $coupon->code,
            'discount' => $coupon->calculateDiscount($subtotal),
        ]);

        return ['success' => true, 'message' => 'Coupon applied successfully!'];
    }

    public function getDiscount(): float
    {
        $applied = Session::get($this->couponKey);
        return $applied['discount'] ?? 0.0;
    }

    public function getCouponCode(): ?string
    {
        $applied = Session::get($this->couponKey);
        return $applied['code'] ?? null;
    }

    public function getTotals(float $shippingCost = 0): array
    {
        $subtotal = $this->getSubtotal();
        $discount = $this->getDiscount();
        $tax = round(($subtotal - $discount) * 0.05, 2); // 5% default tax
        $total = max(0, $subtotal - $discount + $tax + $shippingCost);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping' => $shippingCost,
            'total' => $total,
            'item_count' => array_sum(array_column($this->getCart(), 'quantity')),
        ];
    }
}
