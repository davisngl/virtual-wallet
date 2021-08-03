<?php

namespace Tests\Feature\E2E;

use App\Models\User;
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
}
