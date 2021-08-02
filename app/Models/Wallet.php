<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperWallet
 */
class Wallet extends Model
{
    use HasFactory;

    protected $casts = [
        'user_id' => 'int'
    ];

    protected $fillable = [
        'name',
        'currency',
        'amount',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
