<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Utilities', 'description' => 'Electricity, water, etc.'],
            ['name' => 'Raw Materials', 'description' => 'Flour, sugar, etc.'],
            ['name' => 'Equipment', 'description' => 'Baking equipment'],
            ['name' => 'Maintenance', 'description' => 'Equipment maintenance'],
            ['name' => 'Salaries', 'description' => 'Employee salaries'],
            ['name' => 'Rent', 'description' => 'Building rent'],
            ['name' => 'Other', 'description' => 'Other expenses'],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}