<?php


namespace Tests\Bootstrap;

use App\Category;

class CategoryFactory {

    protected $count;
    protected $type;

    public function count($count)
    {
        $this->count = $count;
        return $this;
    }

    public function type($type)
    {
        if ($type == 'income')
        {
            $this->type = Category::INCOME;
        } else
        {
            $this->type = Category::EXPENSES;
        }
        return $this;
    }

    public function create()
    {
        $category = factory(Category::class, $this->count)
            ->create(['type' => $this->type ?? Category::INCOME]);
        return $category;
    }

}
