<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\FactoryBuilder;
use Illuminate\Foundation\Testing\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
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
        FactoryBuilder::macro('data', function ($attributes = []) {
            return $this->make($attributes)->toArray();
        });

        FactoryBuilder::macro('attachTo', function ($attributes = [], $user = null) {
            if (!$user && !Auth::id()) throw new \Exception('No auth user available');

            return $this->create(array_merge($attributes, [
                'user_id' => $user ?? Auth::id()
            ]));
        });

        TestResponse::macro('assertJsonPaths', function ($path, $expected) {
            foreach ($this->json($path) as $real) {
                PHPUnit::assertEquals($expected, $real);
            }

            return $this;
        });

        Resource::withoutWrapping();
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
