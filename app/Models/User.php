<?php

namespace App\Models;

use App\Exceptions\WalletException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    /**
     * @throws WalletException
     */
    public function createWallet(string $currency, string $name = null): Model|Wallet
    {
        if ($this->wallets()->firstWhere('currency', $currency)) {
            throw WalletException::alreadyExists($currency);
        }

        return $this->wallets()->create([
            'name'     => $name ?: Str::uuid()->toString(),
            'currency' => $currency,
            'amount'   => 0
        ]);
    }

    public function getWallet(string $currency): ?Wallet
    {
        return $this->wallets()->firstWhere('currency', $currency);
    }
}
