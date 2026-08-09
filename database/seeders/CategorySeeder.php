<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics & Gadgets',
                'icon' => 'bi-laptop',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=600&q=80',
                'is_featured' => true,
                'subs' => [
                    [
                        'name' => 'Smartphones & Tablets',
                        'children' => ['Android Phones', 'iPhones & iOS', 'Tablets & iPads']
                    ],
                    [
                        'name' => 'Laptops & Computers',
                        'children' => ['Gaming Laptops', 'MacBooks', 'Computer Components']
                    ],
                    [
                        'name' => 'Smart Wearables',
                        'children' => ['Smartwatches', 'Fitness Trackers', 'VR Headsets']
                    ]
                ]
            ],
            [
                'name' => 'Fashion & Apparel',
                'icon' => 'bi-bag-heart',
                'image' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=600&q=80',
                'is_featured' => true,
                'subs' => [
                    [
                        'name' => 'Men\'s Clothing',
                        'children' => ['Shirts & Polo', 'Jeans & Trousers', 'Jackets & Coats']
                    ],
                    [
                        'name' => 'Women\'s Fashion',
                        'children' => ['Dresses & Sarees', 'Tops & Tees', 'Handbags & Purses']
                    ],
                    [
                        'name' => 'Footwear & Shoes',
                        'children' => ['Sneakers & Sports', 'Formal Shoes', 'Sandals & Boots']
                    ]
                ]
            ],
            [
                'name' => 'Home & Kitchen Appliances',
                'icon' => 'bi-house-heart',
                'image' => 'https://images.unsplash.com/photo-1556911220-e15b29be8c8f?w=600&q=80',
                'is_featured' => true,
                'subs' => [
                    [
                        'name' => 'Kitchen Appliances',
                        'children' => ['Blenders & Juicers', 'Microwaves & Ovens', 'Coffee Makers']
                    ],
                    [
                        'name' => 'Home Comfort',
                        'children' => ['Air Conditioners', 'Vacuum Cleaners', 'Air Purifiers']
                    ]
                ]
            ],
            [
                'name' => 'Beauty & Personal Care',
                'icon' => 'bi-stars',
                'image' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=600&q=80',
                'is_featured' => true,
                'subs' => [
                    [
                        'name' => 'Skincare & Cosmetics',
                        'children' => ['Face Moisturizers', 'Serums & Oils', 'Sunscreen']
                    ],
                    [
                        'name' => 'Hair Care & Fragrance',
                        'children' => ['Shampoos & Conditioners', 'Perfumes & Deodorants']
                    ]
                ]
            ],
            [
                'name' => 'Sports & Outdoor',
                'icon' => 'bi-trophy',
                'image' => 'https://images.unsplash.com/photo-1517649763962-0c623266010b?w=600&q=80',
                'is_featured' => true,
                'subs' => [
                    [
                        'name' => 'Gym & Fitness',
                        'children' => ['Dumbbells & Weights', 'Yoga Mats', 'Treadmills']
                    ]
                ]
            ]
        ];

        foreach ($categories as $index => $cat) {
            $parent = Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'icon' => $cat['icon'],
                'image' => $cat['image'],
                'level' => 0,
                'is_featured' => $cat['is_featured'],
                'status' => true,
                'order' => $index + 1,
            ]);

            foreach ($cat['subs'] as $subIndex => $sub) {
                $subCat = Category::create([
                    'name' => $sub['name'],
                    'slug' => Str::slug($sub['name']),
                    'parent_id' => $parent->id,
                    'level' => 1,
                    'is_featured' => false,
                    'status' => true,
                    'order' => $subIndex + 1,
                ]);

                foreach ($sub['children'] as $childIndex => $childName) {
                    Category::create([
                        'name' => $childName,
                        'slug' => Str::slug($childName),
                        'parent_id' => $subCat->id,
                        'level' => 2,
                        'is_featured' => false,
                        'status' => true,
                        'order' => $childIndex + 1,
                    ]);
                }
            }
        }
    }
}
