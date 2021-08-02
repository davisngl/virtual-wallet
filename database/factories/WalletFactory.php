<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class WalletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wallet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \Exception
     */
    public function definition()
    {
        return [
            'name'     => $this->faker->sentence(),
            'currency' => Arr::random(['eur', 'usd', 'gbp']),
            'amount'   => random_int(0, 999),
            'user_id'  => User::factory()
        ];
    }
}
