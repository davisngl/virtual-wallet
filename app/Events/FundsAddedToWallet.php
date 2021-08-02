<?php

namespace App\Events;

use App\Models\Wallet;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FundsAddedToWallet
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Wallet $wallet;

    public int $amount;

    public string $type;

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
