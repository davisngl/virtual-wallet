<?php

namespace App\Listeners;

use App\Models\Wallet;

class CreateTransaction
{
    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        /** @var Wallet $wallet */
        $wallet = $event->wallet;

        $wallet->transactions()->create([
            'amount'   => $event->amount,
            'currency' => $wallet->currency,
            'type'     => $event->type,
        ]);
    }
}
