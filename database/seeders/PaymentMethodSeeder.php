<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        // Delete unwanted manual MFS methods so only Paymently and COD remain
        PaymentMethod::whereIn('code', ['bkash', 'nagad', 'rocket'])->delete();

        PaymentMethod::updateOrCreate(
            ['code' => 'paymently'],
            [
                'name' => 'Paymently.io Instant Gateway (Cards, Banking, MFS)',
                'code' => 'paymently',
                'logo' => 'https://img.icons8.com/fluency/96/bank-cards.png',
                'instructions' => 'Pay securely using Paymently.io API supporting Visa, Mastercard, AMEX, bKash, Nagad and NetBanking.',
                'is_active' => true,
                'order' => 1,
            ]
        );

        PaymentMethod::updateOrCreate(
            ['code' => 'cod'],
            [
                'name' => 'Cash On Delivery (COD)',
                'code' => 'cod',
                'logo' => 'https://img.icons8.com/fluency/96/cash-in-hand.png',
                'instructions' => 'Pay cash upon receiving your items at your doorstep.',
                'is_active' => true,
                'order' => 2,
            ]
        );
    }
}
