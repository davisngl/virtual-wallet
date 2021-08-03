<?php

namespace App\Models;

use App\Events\FundsAddedToWallet;
use App\Events\FundsWithdrawnFromWallet;
use App\Exceptions\WalletException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperWallet
 */
class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'currency',
        'amount',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->latest();
    }

    /**
     * @throws WalletException
     */
    public function deposit(int $amount): bool
    {
        if ($amount <= 0) {
            throw WalletException::invalidAmount();
        }

        $result = tap($this)->update([
            'amount' => $this->amount + $amount
        ]);

        if (! $result) {
            // Generally, there could be outside factors (bans, suspicious activity or such)
            // that would not allow for adding funds, therefore - this can be thrown.
            throw WalletException::failedAddingFunds();
        }

        event(new FundsAddedToWallet($result, Transaction::TYPE_DEPOSIT, $amount));

        // Just to clarify why I return just "true":
        // method has only 2 options - throw exception or return true
        // from "update" method, therefore it makes sense to just return "true".
        return true;
    }

    /**
     * @throws WalletException
     */
    public function withdraw(int $amount): bool
    {
        if (! $this->canBeWithdrawn($amount)) {
            throw WalletException::insufficientFunds();
        }

        $result = tap($this)->update([
            'amount' => $this->amount - $amount
        ]);

        if (! $result) {
            // Generally, there could be outside factors (bans, suspicious activity or such)
            // that would not allow for adding funds, therefore - this can be thrown.
            throw WalletException::failedWithdrawingFunds();
        }

        event(new FundsWithdrawnFromWallet($result, Transaction::TYPE_WITHDRAW, $amount));

        return true;
    }

    public function canBeWithdrawn(int $amount): bool
    {
        return $amount > 0 && $this->fresh()->hasSufficientFunds($amount);
    }

    public function hasSufficientFunds(int $amount): bool
    {
        return $this->amount >= $amount;
    }
}
