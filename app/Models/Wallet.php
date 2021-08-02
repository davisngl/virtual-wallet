<?php

namespace App\Models;

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
        $result = $this->update([
            'amount' => $this->amount + $amount
        ]);

        if (! $result) {
            // Generally, there could be outside factors (bans, suspicious activity or such)
            // that would not allow for adding funds, therefore - this can be thrown.
            throw WalletException::failedAddingFunds();
        }

        return true;
    }
}
