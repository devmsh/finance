<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        $categories = [
            ['name' => 'Others', 'type' => Category::EXPENSES],
            ['name' => 'Income', 'type' => Category::INCOME],
            ['name' => 'Food', 'type' => Category::EXPENSES],
        ];

        foreach ($categories as $attributes) {
            $attributes['uuid'] = \Ramsey\Uuid\Uuid::uuid4()->toString();
            Category::createWithAttributes($attributes);
        }
    }
}
