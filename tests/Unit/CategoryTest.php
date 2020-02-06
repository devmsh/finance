<?php

namespace Tests\Unit;

use App\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Facades\Tests\Bootstrap\CategoryFactory;


class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_app_include_default_categories()
    {
        $this->seed(\CategorySeeder::class);

        $category = Category::find(1);
        $this->assertEquals('Others', $category->name);
        $this->assertEquals(Category::EXPENSES, $category->type);
    }

    public function test_a_category_has_path()
    {
        $category = CategoryFactory::type('income')->create();

        $this->assertEquals($category->path('income'), 'api/categories?type='.Category::INCOME);
    }
}
