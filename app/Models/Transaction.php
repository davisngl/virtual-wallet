<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
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

    /**
     * Constitutes a transaction still being validated for fraud.
     */
    const VERDICT_VALIDATING = 'validating';

    public static array $availableTypes = [
        self::TYPE_DEPOSIT,
        self::TYPE_WITHDRAW,
    ];

    public static array $availableVerdicts = [
        self::VERDICT_VALIDATING,
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
}