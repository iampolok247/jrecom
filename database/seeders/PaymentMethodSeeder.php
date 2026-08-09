<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
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
            ['code' => 'bkash'],
            [
                'name' => 'bKash Mobile Banking',
                'code' => 'bkash',
                'logo' => 'https://img.icons8.com/color/96/bKash.png',
                'account_number' => '01700000000',
                'merchant_number' => '01800000000',
                'personal_number' => '01900000000',
                'instructions' => 'Please send money to our Merchant / Personal bKash number and enter your Transaction ID at checkout.',
                'is_active' => true,
                'order' => 2,
            ]
        );

        PaymentMethod::updateOrCreate(
            ['code' => 'nagad'],
            [
                'name' => 'Nagad Digital Payment',
                'code' => 'nagad',
                'logo' => 'https://img.icons8.com/color/96/wallet.png',
                'account_number' => '01711111111',
                'merchant_number' => '01811111111',
                'instructions' => 'Send money to Nagad Merchant number and provide Trx ID.',
                'is_active' => true,
                'order' => 3,
            ]
        );

        PaymentMethod::updateOrCreate(
            ['code' => 'rocket'],
            [
                'name' => 'Rocket Mobile Banking',
                'code' => 'rocket',
                'logo' => 'https://img.icons8.com/fluency/96/money-bag-with-card.png',
                'account_number' => '01722222222-7',
                'instructions' => 'Pay via DBBL Rocket Biller Code or Direct Transfer.',
                'is_active' => true,
                'order' => 4,
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
                'order' => 5,
            ]
        );
    }
}
