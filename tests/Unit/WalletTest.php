<?php

namespace Tests\Unit;

use App\Exceptions\WalletException;
use App\Models\User;
use App\Models\Wallet;
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
    public function it_should_create_wallet_with_specified_currency()
    {
        $this->user->createWallet($currency = 'eur', $name = 'My cool wallet');
        $wallet = Wallet::first();

        $this->assertDatabaseCount('wallets', 1);
        $this->assertEquals($currency, $wallet->currency);
        $this->assertEquals($name, $wallet->name);
    }

    /** @test */
    public function when_no_wallet_name_is_provided_it_creates_wallet_with_unique_id()
    {
        $wallet = $this->user->createWallet('eur');

        // For better testing, wallet name should be mocked,
        // so we can provide specific value to test for,
        // then we could use ->assertEquals(... on it.
        // When no name is provided, it won't be null.
        $this->assertIsString($wallet->name);
    }

    /** @test */
    public function it_should_be_able_to_create_multiple_wallets_that_each_have_its_own_currency()
    {
        $this->user->createWallet('eur');

        $this->assertTrue((bool) $this->user->createWallet('usd'));
        $this->assertTrue((bool) $this->user->createWallet('gbp'));
    }

    /** @test */
    public function it_should_not_create_another_wallet_with_the_same_currency_user_already_has()
    {
        $this->user->createWallet($currency = 'eur');

        $this->expectException(WalletException::class);
        $this->expectExceptionMessage(WalletException::alreadyExists($currency)->getMessage());

        $this->user->createWallet('eur');
    }

    /** @test */
    public function it_successfully_returns_a_wallet_by_the_currency_when_needed_currency_is_provided()
    {
        $wallet = $this->user->createWallet($currency = 'eur');

        $this->assertEquals($wallet->toArray(), $this->user->getWallet($currency)->toArray());
    }

    /** @test */
    public function it_returns_null_when_requested_currency_wallet_is_not_found()
    {
        $this->assertNull($this->user->getWallet('non-existent'));
    }
}
