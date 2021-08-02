<?php

namespace App\Models;

use App\Events\FundsAddedToWallet;
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
        return $this->hasMany(Transaction::class);
    }

    /**
     * @throws WalletException
     */
    public function deposit(int $amount): bool
    {
        if ($amount <= 0) {
            throw WalletException::invalidAmount();
        }

        $result = $this->update([
            'amount' => $this->amount + $amount
        ]);

        if (! $result) {
            // Generally, there could be outside factors (bans, suspicious activity or such)
            // that would not allow for adding funds, therefore - this can be thrown.
            throw WalletException::failedAddingFunds();
        }

        event(new FundsAddedToWallet($this, Transaction::TYPE_DEPOSIT, $amount));

        // Just to clarify why I return just "true":
        // method has only 2 options - throw exception or return true
        // from "update" method, therefore it makes sense to just return "true".
        return true;
    }
}
