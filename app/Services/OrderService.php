<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderTimeline;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(array $data, array $cartItems, array $totals): Order
    {
        return DB::transaction(function () use ($data, $cartItems, $totals) {
            $orderNumber = 'JRE-' . strtoupper(date('Ymd')) . '-' . rand(1000, 9999);

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => auth()->id(),
                'billing_name' => $data['billing_name'],
                'billing_email' => $data['billing_email'],
                'billing_phone' => $data['billing_phone'],
                'billing_address' => $data['billing_address'],
                'billing_city' => $data['billing_city'],
                'billing_state' => $data['billing_state'] ?? null,
                'billing_zip' => $data['billing_zip'] ?? null,
                'billing_country' => $data['billing_country'] ?? 'Bangladesh',
                
                'shipping_name' => $data['shipping_name'] ?? $data['billing_name'],
                'shipping_email' => $data['shipping_email'] ?? $data['billing_email'],
                'shipping_phone' => $data['shipping_phone'] ?? $data['billing_phone'],
                'shipping_address' => $data['shipping_address'] ?? $data['billing_address'],
                'shipping_city' => $data['shipping_city'] ?? $data['billing_city'],
                'shipping_state' => $data['shipping_state'] ?? null,
                'shipping_zip' => $data['shipping_zip'] ?? null,
                'shipping_country' => $data['shipping_country'] ?? 'Bangladesh',
                
                'subtotal' => $totals['subtotal'],
                'discount' => $totals['discount'],
                'coupon_code' => $data['coupon_code'] ?? null,
                'tax' => $totals['tax'],
                'shipping_cost' => $totals['shipping'],
                'total_amount' => $totals['total'],
                
                'payment_method_id' => $data['payment_method_id'] ?? null,
                'payment_method_name' => $data['payment_method_name'] ?? 'Cash On Delivery',
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'product_sku' => $item['sku'] ?? null,
                    'variant_info' => $item['variant'] ?? null,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                ]);

                // Deduct stock & increment sales count
                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('stock', $item['quantity']);
                    $product->increment('sales_count', $item['quantity']);
                }
            }

            OrderTimeline::create([
                'order_id' => $order->id,
                'status' => 'Order Placed',
                'comment' => 'Order placed successfully by customer via ' . $order->payment_method_name,
                'created_by' => auth()->user() ? auth()->user()->name : 'Guest Customer',
            ]);

            return $order;
        });
    }

    public function updateOrderStatus(Order $order, string $status, ?string $comment = null): Order
    {
        $order->update(['order_status' => $status]);

        if ($status === 'delivered') {
            $order->update(['payment_status' => 'paid']);
        }

        OrderTimeline::create([
            'order_id' => $order->id,
            'status' => 'Status changed to ' . ucfirst($status),
            'comment' => $comment ?? 'Order status updated by admin.',
            'created_by' => auth()->user() ? auth()->user()->name : 'Admin System',
        ]);

        return $order;
    }
}
