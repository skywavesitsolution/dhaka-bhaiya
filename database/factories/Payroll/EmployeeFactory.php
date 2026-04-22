<?php

namespace Database\Factories\Payroll;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payroll\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $openingBalance = fake()->numberBetween(10000, 50000);
        $openingAdvanceBalance = fake()->numberBetween(0, 10000);

        return [
            'name' => fake()->name(),
            'opening_balance' => $openingBalance,
            'balance' => $openingBalance,
            'opening_advance_balance' => $openingAdvanceBalance,
            'advance_balance' => $openingAdvanceBalance,
            'basic_salary' => fake()->numberBetween(10000, 50000),
            'email' => fake()->email(),
            'phone' => fake()->phoneNumber(),
            'joining_date' => fake()->date(),
            'address' => fake()->address(),
            'user_id' => fake()->randomNumber(3),
        ];
    }
}
