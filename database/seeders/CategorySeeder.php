<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Tapalapa', 'description' => 'Tapalapa bakery'],
            ['name' => 'Senfur', 'description' => 'Senfur bakery'],
            ['name' => 'Pastry', 'description' => 'Pastry bakery'],
            ['name' => 'Combination', 'description' => 'Mixed products bakery'],
            ['name' => 'All', 'description' => 'All types of products'],
            ['name' => 'Other', 'description' => 'Other types of bakery'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}