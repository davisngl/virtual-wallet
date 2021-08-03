<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use App\Rules\HasSufficientFunds;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $availableTypes = implode(',', Transaction::$availableTypes);

        return [
            'type'     => "required|in:{$availableTypes}",
            'amount'   => ['required', 'int', 'min:1', 'max:999999999', new HasSufficientFunds(wallet: $this->route('wallet'), type: $this->type)],
        ];
    }
}
