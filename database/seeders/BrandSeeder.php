<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple', 'logo' => 'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=300&q=80'],
            ['name' => 'Samsung', 'logo' => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=300&q=80'],
            ['name' => 'Sony', 'logo' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=300&q=80'],
            ['name' => 'Nike', 'logo' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=300&q=80'],
            ['name' => 'Adidas', 'logo' => 'https://images.unsplash.com/photo-1518002171953-a080ee817e1f?w=300&q=80'],
            ['name' => 'Xiaomi', 'logo' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300&q=80'],
            ['name' => 'Asus', 'logo' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=300&q=80'],
            ['name' => 'LG Electronics', 'logo' => 'https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=300&q=80'],
        ];

        foreach ($brands as $index => $b) {
            Brand::create([
                'name' => $b['name'],
                'slug' => Str::slug($b['name']),
                'logo' => $b['logo'],
                'is_featured' => true,
                'status' => true,
                'order' => $index + 1,
            ]);
        }
    }
}
