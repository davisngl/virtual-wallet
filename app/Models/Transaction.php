<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTransaction
 */
class Transaction extends Model
{
    use HasFactory;

    protected $casts = [
        'amount'    => 'int',
        'wallet_id' => 'int',
    ];

    protected $fillable = [
        'amount',
        'currency',
        'type',
        'verdict',
    ];

    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAW = 'withdraw';

    const VERDICT_OK = 'ok';

    /**
     * Constitutes a fraudulent transaction.
     */
    const VERDICT_FRAUDULENT = 'fraudulent';

    public static array $availableTypes = [
        self::TYPE_DEPOSIT,
        self::TYPE_WITHDRAW,
    ];

    public static array $availableVerdicts = [
        self::VERDICT_OK,
        self::VERDICT_FRAUDULENT,
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    /**
     * Get only in-going money into wallet.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnlyIngoing(Builder $query): Builder
    {
        return $query->where('type', static::TYPE_DEPOSIT);
    }

    /**
     * Get only out-going money into wallet.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnlyOutgoing(Builder $query): Builder
    {
        return $query->where('type', static::TYPE_WITHDRAW);
    }

    public function markAsFraudulent(): bool
    {
        return $this->update([
            'verdict' => self::VERDICT_FRAUDULENT
        ]);
    }

    public function fraudulent(): bool
    {
        return $this->verdict === self::VERDICT_FRAUDULENT;
    }

    public function ok(): bool
    {
        return $this->verdict === self::VERDICT_OK;
    }
}
