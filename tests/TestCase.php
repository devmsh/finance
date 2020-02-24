<?php

namespace Tests;

use App\Http\Requests\GoalRequest;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use JMac\Testing\Traits\HttpTestAssertions;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, HttpTestAssertions;

    public $validator;

    protected function setUp(): void
    {
        parent::setUp();

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[DatabaseMigrations::class])) {
            $this->runDatabaseMigrations();
        }
        $this->validator = app()->get('validator');
    }

    public function passportAs($user, $scopes = [], $guard = 'api')
    {
        Passport::actingAs($user, $scopes, $guard);

        return $this;
    }

    protected function validate($mockedRequestData, $rules)
    {
        return $this->validator
            ->make($mockedRequestData, $rules)
            ->passes();
    }
}
