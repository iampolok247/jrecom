<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\ShippingClass;
use App\Models\Size;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Units
        foreach ([['name' => 'Piece', 'code' => 'pc'], ['name' => 'Kilogram', 'code' => 'kg'], ['name' => 'Box', 'code' => 'box'], ['name' => 'Pair', 'code' => 'pair']] as $u) {
            Unit::create($u);
        }

        // Colors
        foreach ([
            ['name' => 'Midnight Black', 'code' => '#111827'],
            ['name' => 'Space Grey / Silver', 'code' => '#6b7280'],
            ['name' => 'Ocean Navy Blue', 'code' => '#1e3a8a'],
            ['name' => 'Crimson Red', 'code' => '#dc2626'],
            ['name' => 'Rose Gold', 'code' => '#f43f5e'],
            ['name' => 'Emerald Green', 'code' => '#059669'],
        ] as $c) {
            Color::create($c);
        }

        // Sizes
        foreach ([
            ['name' => 'Small', 'code' => 'S'],
            ['name' => 'Medium', 'code' => 'M'],
            ['name' => 'Large', 'code' => 'L'],
            ['name' => 'Extra Large', 'code' => 'XL'],
            ['name' => 'XXL', 'code' => 'XXL'],
            ['name' => '256GB / 8GB RAM', 'code' => '256-8'],
            ['name' => '512GB / 12GB RAM', 'code' => '512-12'],
        ] as $s) {
            Size::create($s);
        }

        // Shipping Classes
        ShippingClass::create(['name' => 'Standard Delivery', 'cost' => 60.00, 'estimated_days' => 3]);
        ShippingClass::create(['name' => 'Express Express Shipping', 'cost' => 120.00, 'estimated_days' => 1]);
        ShippingClass::create(['name' => 'Heavy Item Cargo', 'cost' => 250.00, 'estimated_days' => 5]);
    }
}
