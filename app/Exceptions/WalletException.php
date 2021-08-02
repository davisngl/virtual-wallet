<?php

namespace App\Exceptions;

use Exception;

class WalletException extends Exception
{
    public static function alreadyExists(string $currency): static
    {
        return new static(
            "Wallet with {$currency} already exists. You cannot create another wallet with the same currency."
        );
    }
}
