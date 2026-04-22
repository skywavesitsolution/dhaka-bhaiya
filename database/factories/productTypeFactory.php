<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class productTypeFactory extends Factory
{

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'user_id' => fake()->randomNumber(3)
        ];
    }
}
