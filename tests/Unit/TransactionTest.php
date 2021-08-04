<?php

namespace Tests\Unit;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->actingAs($this->user = User::factory()->create());
    }

    /** @test */
    public function it_can_scope_down_only_ingoing_transactions()
    {
        $wallet = $this->user->createWallet('eur');

        $wallet->deposit(400);
        $wallet->deposit(250);
        $wallet->deposit(79);

        $this->assertCount(3, $deposits = Transaction::onlyIngoing()->get());

        $deposits->each(function (Transaction $transaction) {
            $this->assertTrue($transaction->type === Transaction::TYPE_DEPOSIT);
        });
    }

    /** @test */
    public function it_can_scope_down_only_outgoing_transactions()
    {
        $wallet = $this->user->createWallet('eur');
        $wallet->deposit(1000);

        $wallet->withdraw(400);
        $wallet->withdraw(250);
        $wallet->withdraw(79);

        $this->assertCount(3, $deposits = Transaction::onlyOutgoing()->get());

        $deposits->each(function (Transaction $transaction) {
           $this->assertTrue($transaction->type === Transaction::TYPE_WITHDRAW);
        });
    }

    /** @test */
    public function it_can_be_marked_as_fraudulent()
    {
        $wallet = $this->user->createWallet('eur');
        $wallet->deposit(1000);

        /** @var Transaction $lastTransaction */
        $lastTransaction = $wallet->transactions()->first();
        $this->assertEquals(Transaction::VERDICT_OK, $lastTransaction->verdict);

        $lastTransaction->markAsFraudulent();
        $this->assertEquals(Transaction::VERDICT_FRAUDULENT, $lastTransaction->verdict);
    }
}
