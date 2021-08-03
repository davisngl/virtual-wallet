<?php

namespace App\Http\Requests;

use App\Rules\UniqueCurrencyWalletForUser;
use Illuminate\Foundation\Http\FormRequest;

class StoreWalletRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name'     => 'required|min:3|unique:wallets,name',
            'currency' => ['required', 'min:3', new UniqueCurrencyWalletForUser]
        ];
    }
}
