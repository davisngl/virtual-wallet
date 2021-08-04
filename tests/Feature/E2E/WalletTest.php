<?php

namespace Tests\Feature\E2E;

use App\Models\Transaction;
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
    public function unauthenticated_user_cannot_access_dashboard()
    {
        // If dashboard cannot be access, nothing of value & in business logic
        // cannot be access by guest.
        auth()->logout();

        $this->assertGuest();
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function it_shows_dashboard_page()
    {
        $this->get(route('dashboard'))
            ->assertStatus(200)
            ->assertSee('Dashboard');
    }

    /** @test */
    public function it_shows_a_page_with_all_wallets()
    {
        $this->user->createWallet('eur', $eurWalletName = 'EUR wallet for bills');
        $this->user->createWallet('usd', $usdWalletName = 'USD wallet for big bucks');

        $this->get(route('wallet.index'))
            ->assertStatus(200)
            ->assertSee([$eurWalletName, $usdWalletName]);
    }

    /** @test */
    public function it_shows_wallet_creation_form()
    {
        $this->get(route('wallet.create'))
            ->assertStatus(200)
            ->assertSee(['Create a Wallet', 'Wallet Name', 'Currency']);
    }

    /** @test */
    public function submitting_form_for_wallet_creation_should_return_success()
    {
        $this->post(route('wallet.store'), $attributes = [
            'name'     => 'My cool wallet for eur',
            'currency' => 'eur',
        ])
            ->assertSessionDoesntHaveErrors(['name', 'currency'])
            ->assertStatus(302)
            ->assertRedirect(route('wallet.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('wallets', $attributes);
    }

    /** @test */
    public function it_shows_all_the_transactions_for_specified_wallet()
    {
        $wallet = $this->user->createWallet('eur');
        $wallet->deposit(99);

        $this->get(route('transaction.index', ['wallet' => $wallet->id]))
            ->assertStatus(200)
            ->assertSee([
                "Transactions for wallet ID: {$wallet->id}",
                'Amount: 99',
                'Currency: EUR',
                'Type: Deposit',
            ]);
    }

    /** @test */
    public function it_can_successfully_delete_a_transaction()
    {
        $wallet = $this->user->createWallet('eur');
        $wallet->deposit(99);

        $this->delete(route('transaction.destroy', [
            'wallet'      => $wallet->id,
            'transaction' => $wallet->transactions()->latest()->first()->id,
        ]))
            ->assertRedirect(route('transaction.index', ['wallet' => $wallet->id]));
    }

    /** @test */
    public function it_can_successfully_mark_transaction_as_fraudulent()
    {
        $wallet = $this->user->createWallet('eur');
        $wallet->deposit(99);

        $transaction = $wallet->transactions()->latest()->first();

        $routeParams = [
            'transaction' => $transaction->id,
        ];

        $this->followingRedirects()
            ->patch(
                route('transaction.verdict', $routeParams),
                ['verdict' => Transaction::VERDICT_FRAUDULENT]
            )
            ->assertSessionDoesntHaveErrors()
            ->assertDontSee('Mark as fraudulent')
            ->assertSee('Verdict: Fraudulent');

        $this->assertTrue($transaction->fresh()->fraudulent());
    }

    /** @test */
    public function it_shows_transaction_creation_form()
    {
        $wallet = $this->user->createWallet('eur');

        $this->get(route('transaction.create', ['wallet' => $wallet->id]))
            ->assertSuccessful()
            ->assertSee('Create a Transaction');
    }

    /** @test */
    public function it_can_successfully_create_appropriate_transaction_for_wallet()
    {
        $wallet = $this->user->createWallet('eur');
        $formData = [
            'amount' => $amount = 400,
            'type'   => Transaction::TYPE_DEPOSIT,
        ];

        $this->followingRedirects()
            ->post(route('transaction.store', ['wallet' => $wallet->id]), $formData)
            ->assertSessionHasNoErrors()
            ->assertSee([
                "Amount: {$amount}",
                "Type: Deposit",
            ]);

        $this->assertCount(1, $wallet->transactions);
        $this->assertEquals($amount, Transaction::firstWhere('wallet_id', $wallet->id)->amount);
    }

    /** @test */
    public function wallet_statements_page_displays_all_transactions()
    {
        $wallet = $this->user->createWallet('usd');

        $wallet->deposit(1000);
        $wallet->deposit(400);

        $wallet->withdraw(300);
        $wallet->withdraw(100);
        $wallet->withdraw(200);

        $this->get(route('wallet.statements', ['wallet' => $wallet->id]))
            ->assertSuccessful()
            ->assertSee([
                'Total in-coming: 1400',
                'Total out-going: 600',
            ]);
    }

    /** @test */
    public function wallet_can_be_renamed()
    {
        $wallet = $this->user->createWallet('usd', $initialName = 'Cool wallet');

        $this->assertEquals($initialName, Wallet::first()->name);

        $this->followingRedirects()
            ->post(route('wallet.update', ['wallet' => $wallet]), [
                'name' => $updatedName = 'Much cooler wallet',
            ])
            ->assertSuccessful()
            ->assertSee($updatedName);

        $this->assertEquals($updatedName, $wallet->fresh()->name);
    }
}
