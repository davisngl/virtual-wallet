<?php

namespace App\Rules;

use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Contracts\Validation\Rule;

class HasSufficientFunds implements Rule
{
    private Wallet $wallet;

    private string $type;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Wallet $wallet, string $type)
    {
        $this->wallet = $wallet;
        $this->type = $type ?? Transaction::TYPE_WITHDRAW;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->type === Transaction::TYPE_DEPOSIT ||
            $this->type === Transaction::TYPE_WITHDRAW && $this->wallet->hasSufficientFunds($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Your wallet has insufficient funds to make a withdraw.';
    }
}
