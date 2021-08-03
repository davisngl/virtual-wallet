<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AllowedCurrency implements Rule
{
    private ?array $allowedCurrencies;

    /**
     * @param array $allowedCurrencies External list of allowed currencies (lowercase, 3-letter symbols)
     */
    public function __construct(array $allowedCurrencies = [])
    {
        // I could validate that every passed in currency code is lower-case
        // and only 3-letters, but that should be developers' fault either way.
        $this->allowedCurrencies = $allowedCurrencies;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return in_array(
            strtolower($value),
            $this->getAllowedCurrencies()
        );
    }

    private function getAllowedCurrencies()
    {
        return count($this->allowedCurrencies)
            ? $this->allowedCurrencies
            : [
                'eur', 'usd', 'gbp',
                'hrk', 'czk', 'dkk',
                'gel', 'huf', 'chf',
            ];
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $allowedCurrencies = implode(', ', $this->getAllowedCurrencies());

        return "Currency is not allowed. Allowed currencies: {$allowedCurrencies}.";
    }
}
