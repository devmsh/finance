<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['name' => 'Others', 'type' => Category::EXPENSES_TYPE],
            ['name' => 'Income', 'type' => Category::INCOME_TYPE],
            ['name' => 'Food', 'type' => Category::EXPENSES_TYPE],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
