<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Testing\Assert as PHPUnit;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        FactoryBuilder::macro('data', function ($attributes) {
            return $this->make($attributes)->toArray();
        });

        TestResponse::macro('assertJsonPaths',function($path, $expected){
            foreach ($this->json($path) as $real) {
                PHPUnit::assertEquals($expected, $real);
            }

            return $this;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
