<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        FactoryBuilder::macro('data',function($attributes){
            return $this->make($attributes)->toArray();
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
