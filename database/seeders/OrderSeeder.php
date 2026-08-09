<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'customer')->get();
        if ($users->isEmpty()) {
            return;
        }

        $products = Product::all();
        if ($products->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['paid', 'paid', 'paid', 'pending', 'failed'];

        $orderIndex = 1000;

        // Generate 36 realistic orders spread across the last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $ordersInMonth = rand(5, 8);

            for ($j = 0; $j < $ordersInMonth; $j++) {
                $orderIndex++;
                $user = $users->random();
                $product = $products->random();
                
                $createdDate = $monthDate->copy()->addDays(rand(1, 25))->addHours(rand(1, 20));
                $status = $statuses[array_rand($statuses)];
                $paymentStatus = ($status === 'cancelled') ? 'failed' : $paymentStatuses[array_rand($paymentStatuses)];

                $quantity = rand(1, 3);
                $price = $product->effective_price;
                $subtotal = $price * $quantity;
                $tax = round($subtotal * 0.05, 2);
                $shipping = 100.00;
                $totalAmount = $subtotal + $tax + $shipping;

                Order::create([
                    'order_number' => 'ORD-2026-' . $orderIndex,
                    'user_id' => $user->id,
                    'billing_name' => $user->name,
                    'billing_email' => $user->email,
                    'billing_phone' => '+880 1711 ' . rand(100000, 999999),
                    'billing_address' => rand(10, 99) . ' Green Road, Farmgate',
                    'billing_city' => 'Dhaka',
                    'billing_state' => 'Dhaka Division',
                    'billing_zip' => '1215',
                    'billing_country' => 'Bangladesh',
                    'shipping_name' => $user->name,
                    'shipping_email' => $user->email,
                    'shipping_phone' => '+880 1711 ' . rand(100000, 999999),
                    'shipping_address' => rand(10, 99) . ' Green Road, Farmgate',
                    'shipping_city' => 'Dhaka',
                    'shipping_state' => 'Dhaka Division',
                    'shipping_zip' => '1215',
                    'shipping_country' => 'Bangladesh',
                    'subtotal' => $subtotal,
                    'discount' => 0,
                    'tax' => $tax,
                    'shipping_cost' => $shipping,
                    'total_amount' => $totalAmount,
                    'payment_method_id' => 1,
                    'payment_method_name' => 'Paymently.io API / bKash',
                    'payment_status' => $paymentStatus,
                    'order_status' => $status,
                    'transaction_id' => 'TXN' . strtoupper(bin2hex(random_bytes(4))),
                    'created_at' => $createdDate,
                    'updated_at' => $createdDate,
                ]);
            }
        }
    }
}
