<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function passportAs($user, $scopes = [], $guard = 'api')
    {
        Passport::actingAs($user, $scopes, $guard);

        return $this;
    }
}
