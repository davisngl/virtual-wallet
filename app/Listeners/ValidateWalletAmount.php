<?php

namespace App\Listeners;

use App\Models\Wallet;

class ValidateWalletAmount
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

        $income = (int) $wallet->transactions()->onlyIngoing()->sum('amount');
        $expenses = (int) $wallet->transactions()->onlyOutgoing()->sum('amount');

        $wallet->update([
            'amount' => $income - $expenses,
        ]);
    }
}
