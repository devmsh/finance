<?php

namespace Tests\Feature;

use App\Category;
use App\Transaction;
use App\Wallet;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

/**
 * @property Category salary
 * @property Category commission
 * @property Category food
 * @property Category health
 */
class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_wallet_can_track_income_with_category()
    {
        $this->withoutExceptionHandling();

        $wallet = factory(Wallet::class)->create();

        $category = factory(Category::class)->create([
            'type' => Category::INCOME,
        ]);

        $response = $this->post("api/wallets/{$wallet->id}/income", [
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'note' => 'Salary',
            'amount' => 1000,
            'category_id' => $category->id,
        ]);

        $response->assertSuccessful();

        $transaction = Transaction::uuid($uuid);
        $this->assertEquals('Salary', $transaction->note);
        $this->assertEquals(1000, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }

    public function test_wallet_can_track_expenses_with_category()
    {
        $wallet = factory(Wallet::class)->create();

        $category = factory(Category::class)->create([
            'type' => Category::EXPENSES,
        ]);

        $response = $this->post("api/wallets/{$wallet->id}/expenses", [
            'uuid' => $uuid = Uuid::uuid4()->toString(),
            'note' => 'Restaurant',
            'amount' => 100,
            'category_id' => $category->id,
        ]);

        $response->assertSuccessful();

        $transaction = Transaction::uuid($uuid);
        $this->assertEquals('Restaurant', $transaction->note);
        $this->assertEquals(-100, $transaction->amount);
        $this->assertInstanceOf(Wallet::class, $transaction->trackable);
    }
}
