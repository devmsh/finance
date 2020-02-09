<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[DatabaseMigrations::class])) {
            $this->runDatabaseMigrations();
        }
    }

    public function passportAs($user, $scopes = [], $guard = 'api')
    {
        Passport::actingAs($user, $scopes, $guard);

        return $this;
    }
}
