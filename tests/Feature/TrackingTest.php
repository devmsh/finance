<?php

namespace Tests\Feature;

use App\Category;
use App\Transaction;
use App\User;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use DatabaseMigrations;

    public function test_wallet_can_track_income_with_category()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $wallet = factory(Wallet::class)->create();

        $category = factory(Category::class)->create([
            'type' => Category::INCOME,
        ]);

        $response = $this->post("api/wallets/{$wallet->id}/income", [
            'note' => 'Salary',
            'amount' => 1000,
            'category_id' => $category->id,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'note',
            'amount',
        ]);

        $transaction = Transaction::find(1);
        $this->assertEquals('Salary', $transaction->note);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertEquals($user->id, $transaction->user_id);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_wallet_can_track_expenses_with_category()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $wallet = factory(Wallet::class)->create();

        $category = factory(Category::class)->create([
            'type' => Category::EXPENSES,
        ]);

        $response = $this->post("api/wallets/{$wallet->id}/expenses", [
            'note' => 'Restaurant',
            'amount' => 100,
            'category_id' => $category->id,
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            'id',
            'note',
            'amount',
        ]);

        $transaction = Transaction::find(1);
        $this->assertEquals('Restaurant', $transaction->note);
        $this->assertEquals(-100, $transaction->amount);
        $this->assertEquals($user->id, $transaction->user_id);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_can_track_bulk_daily_expenses()
    {
        Passport::actingAs($user = factory(User::class)->create());

        $firstWallet = factory(Wallet::class)->create();
        $firstCategory = factory(Category::class)->create([
            'type' => Category::EXPENSES,
        ]);

        $secondWallet = factory(Wallet::class)->create();
        $secondCategory = factory(Category::class)->create([
            'type' => Category::EXPENSES,
        ]);

        $response = $this->post('api/expenses', [
            [
                'note' => 'Restaurant',
                'amount' => 100,
                'wallet_id' => $firstWallet->id,
                'category_id' => $firstCategory->id,
            ], [
                'note' => 'Health',
                'amount' => 200,
                'wallet_id' => $secondWallet->id,
                'category_id' => $secondCategory->id,
            ],
        ]);

        $response->assertSuccessful();
        $response->assertJsonStructure([
            [
                'id',
                'note',
                'amount',
            ],
        ]);

        $transaction = Transaction::find(1);
        $this->assertEquals('Restaurant', $transaction->note);
        $this->assertEquals(-100, $transaction->amount);
        $this->assertEquals($firstCategory->id, $transaction->category_id);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
        $this->assertEquals($user->id, $transaction->user_id);
        $this->assertEquals($firstWallet->id, $transaction->trackable->id);

        $transaction = Transaction::find(2);
        $this->assertEquals('Health', $transaction->note);
        $this->assertEquals(-200, $transaction->amount);
        $this->assertEquals($secondCategory->id, $transaction->category_id);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
        $this->assertEquals($user->id, $transaction->user_id);
        $this->assertEquals($secondWallet->id, $transaction->trackable->id);
    }
}
