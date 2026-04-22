<?php

namespace Database\Factories\Account;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class AccountFactory extends Factory
{

    public function definition(): array
    {
        $openingBalance = fake()->randomNumber(3);
        return [
            'account_name' => fake()->name,
            'opening_balance' => $openingBalance,
            'balance' => $openingBalance,
            'account_number' => "" . fake()->randomNumber(9) . "",
            'user_id' => fake()->randomNumber(1),
        ];
    }
}
