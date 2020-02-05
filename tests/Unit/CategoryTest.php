<?php

namespace Tests\Unit;

use App\Category;
use App\User;
use CategorySeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_app_include_default_categories()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::find(1);
        $this->assertEquals('Others', $category->name);
        $this->assertEquals(Category::EXPENSES_TYPE, $category->type);
    }

    public function test_app_create_copy_of_the_categories_for_each_user()
    {
        factory(Category::class, 5)->create();

        $this->assertEquals(5, Category::count());

        $user = factory(User::class)->create();

        $this->assertEquals(10, Category::count());
        $this->assertEquals(5, $user->categories()->count());
    }
}
