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
            ['name' => 'Others', 'type' => Category::EXPENSES],
            ['name' => 'Income', 'type' => Category::INCOME],
            ['name' => 'Food', 'type' => Category::EXPENSES],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
