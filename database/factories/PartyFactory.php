<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class PartyFactory extends Factory
{

    public function definition(): array
    {
        $openingBalance = fake()->randomNumber(3);
        return [
            'name' => fake()->name,
            'type' => fake()->randomElements(['Marka', 'Driver', 'Customer'])[0],
            'opening_balance' => $openingBalance,
            'balance' => $openingBalance,
            'email' => fake()->email(),
            'company_name' => fake()->company(),
            'address' => fake()->address(),
            'user_id' => fake()->randomNumber(1),
        ];
    }
}
