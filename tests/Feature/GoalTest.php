<?php

namespace Tests\Feature;

use App\Goal;
use App\Http\Requests\GoalRequest;
use App\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoalTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_specify_a_goal()
    {
        $this->passportAs($user = factory(User::class)->create())
            ->post('/api/goals', [
                'name' => 'Home',
                'total' => 1000,
                'due_date' => $due_date = Carbon::today()->addYear(),
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'name' => 'Home',
                'total' => 1000,
                'due_date' => $due_date,
            ]);
    }

    public function test_goal_tracks_some_transactions()
    {
        $goal = factory(Goal::class)->attachTo([], $user = factory(User::class)->create());

        $this->passportAs($user)
            ->post("/api/goals/{$goal->id}/transactions", [
                'note' => 'feb amount',
                'amount' => 100,
            ])
            ->assertSuccessful()
            ->assertJson([
                'id' => 1,
                'note' => 'feb amount',
                'amount' => 100,
            ]);
    }

    /**
     * @dataProvider validationProvider
     * @param $shouldPass
     * @param $mockedRequestData
     */
    public function test_validation_rules_for_goal($shouldPass, $mockedRequestData)
    {
        $rules = (new GoalRequest())->rules();
        $this->assertEquals(
            $shouldPass,
            $this->validate($mockedRequestData, $rules)
        );
    }

    public function validationProvider()
    {
        $faker = Factory::create(Factory::DEFAULT_LOCALE);

        return [
            'request_should_fail_when_no_name_is_provided' => [
                'passed' => false,
                'data' => [
                    'total' => $faker->numberBetween(1, 1000),
                    'due_date' => $faker->date(),
                ]
            ],
            'request_should_fail_when_no_total_is_provided' => [
                'passed' => false,
                'data' => [
                    'name' => $faker->name,
                    'due_date' => $faker->date(),
                ]
            ],
            'request_should_fail_when_no_due_date_is_provided' => [
                'passed' => false,
                'data' => [
                    'name' => $faker->name,
                    'total' => $faker->numberBetween(1, 1000),
                ]
            ],
            'request_should_fail_when_invalid_due_date_is_provided' => [
                'passed' => false,
                'data' => [
                    'name' => $faker->name,
                    'total' => $faker->numberBetween(1, 1000),
                    'due_date' => $faker->name,
                ]
            ],
            'request_should_pass_when_data_is_provided' => [
                'passed' => true,
                'data' => [
                    'name' => $faker->name,
                    'total' => $faker->numberBetween(1, 1000),
                    'due_date' => $faker->date(),
                ]
            ],
        ];
    }
}
