<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            AttributeSeeder::class,
            ProductSeeder::class,
            PaymentMethodSeeder::class,
            SiteSettingSeeder::class,
            HomepageSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
