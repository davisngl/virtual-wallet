<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user = User::factory()->create());
    }

    /** @test */
    public function when_deposit_to_wallet_is_made_wallet_amount_is_updated_and_transaction_is_created()
    {
        $wallet = $this->user->createWallet('eur');

        $this->assertEquals(0, $wallet->amount);

        $wallet->deposit($amount = 1000);

        $this->assertEquals($amount, $wallet->amount);
        $this->assertCount(1, $wallet->transactions);

        $transaction = $wallet->transactions()->first();
        $this->assertEquals($amount, $transaction->amount);
        $this->assertEquals(Transaction::TYPE_DEPOSIT, $transaction->type);

        // Another deposit should show more money in wallet,
        // as well as another transaction.
        $wallet->deposit(500);

        $this->assertEquals($amount + 500, $wallet->amount);
        $this->assertCount(2, $wallet->fresh()->transactions);
    }
}
