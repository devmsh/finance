<?php

namespace Tests\Unit;

use App\Category;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_app_include_default_categories()
    {
        $this->seed(\CategorySeeder::class);

        $category = Category::find(1);
        $this->assertEquals("Others", $category->name);
        $this->assertEquals(Category::EXPENSES, $category->type);
    }
}
