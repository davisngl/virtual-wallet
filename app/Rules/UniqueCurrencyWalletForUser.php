<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class UniqueCurrencyWalletForUser implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /** @var User $user */
        $user = auth()->user();

        return $user->wallets()->where('currency', strtolower($value))->doesntExist();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'User already has wallet with provided currency.';
    }
}
