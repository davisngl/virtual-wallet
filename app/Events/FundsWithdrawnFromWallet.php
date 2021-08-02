<?php

namespace App\Events;

use App\Models\Wallet;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FundsWithdrawnFromWallet
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Wallet $wallet;

    public string $type;

    public int $amount;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Wallet $wallet, string $type, int $amount)
    {
        $this->wallet = $wallet;
        $this->type = $type;
        $this->amount = $amount;
    }
}
