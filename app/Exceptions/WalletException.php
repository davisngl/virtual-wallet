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

    public static function failedAddingFunds(): static
    {
        return new static(
            "Failed adding funds to wallet. Try again later."
        );
    }

    public static function failedWithdrawingFunds(): static
    {
        return new static(
            "Failed withdrawing funds from wallet. Try again later."
        );
    }

    public static function invalidAmount(): static
    {
        return new static(
            "Invalid amount provided. Amount must be positive number."
        );
    }

    public static function insufficientFunds(): static
    {
        return new static(
            "Wallet has insufficient funds. Before trying again, make sure wallet has sufficient funds to make desired withdraw."
        );
    }
}
