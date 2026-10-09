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
            ['name' => 'Books and Study Materials', 'icon' => 'book', 'description' => 'Textbooks, notes and reference materials.'],
            ['name' => 'Electronics', 'icon' => 'device', 'description' => 'Laptops, phones, headphones and gadgets.'],
            ['name' => 'Calculators', 'icon' => 'calculator', 'description' => 'Scientific and programmable calculators.'],
            ['name' => 'Cycles and Transport', 'icon' => 'bike', 'description' => 'Bicycles and student transport.'],
            ['name' => 'Hostel Essentials', 'icon' => 'home', 'description' => 'Fans, kettles, bedsheets and more.'],
            ['name' => 'Clothing and Accessories', 'icon' => 'shirt', 'description' => 'Clothing, bags and accessories.'],
            ['name' => 'Sports Equipment', 'icon' => 'ball', 'description' => 'Rackets, balls and fitness gear.'],
            ['name' => 'Stationery', 'icon' => 'pen', 'description' => 'Notebooks, pens and academic supplies.'],
            ['name' => 'Other', 'icon' => 'box', 'description' => 'Everything else students buy and sell.'],
        ];

        foreach ($categories as $index => $category) {
            Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'icon' => $category['icon'],
                    'description' => $category['description'],
                    'is_active' => true,
                    'sort_order' => $index,
                ],
            );
        }
    }
}
