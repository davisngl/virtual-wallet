<?php

namespace App\Providers;

use App\Events\FundsAddedToWallet;
use App\Events\FundsWithdrawnFromWallet;
use App\Events\TransactionDeleted;
use App\Listeners\CreateTransaction;
use App\Listeners\ValidateWalletAmount;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class               => [
            SendEmailVerificationNotification::class,
        ],
        FundsAddedToWallet::class       => [
            CreateTransaction::class,
            ValidateWalletAmount::class,
        ],
        FundsWithdrawnFromWallet::class => [
            CreateTransaction::class,
            ValidateWalletAmount::class,
        ],
        TransactionDeleted::class       => [
            ValidateWalletAmount::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
