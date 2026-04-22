<?php

namespace Tests\Feature\Account;

use App\Models\Account\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AccountControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testAddAccount(): void
    {
        $user = User::factory()->create();

        $openingBalance = fake()->randomNumber(3);
        $payload = [
            'account_name' => fake()->name,
            'openingBalance' => $openingBalance,
            'accountNumber' => "" . fake()->randomNumber(9) . "",
        ];

        $this->actingAs($user)->post('/add-account', $payload);

        $this->assertDatabaseHas('accounts', [
            'account_name' => $payload['account_name'],
            'opening_balance' => $payload['openingBalance'],
            'balance' => $payload['openingBalance'],
            'account_number' => $payload['accountNumber'],
            'user_id' => Auth::user()->id,
        ]);
    }

    public function testUpdateAccount(): void
    {
        $user = User::factory()->create();
        $account = Account::factory()->create();

        $payload = [
            'account_name' => fake()->name,
            'accountNumber' => "" . fake()->randomNumber(9) . "",
        ];

        $this->actingAs($user)->post("/update-account/{$account->id}", $payload);
        $this->assertDatabaseHas('accounts', [
            'account_name' => $payload['account_name'],
            'account_number' => $payload['accountNumber'],
            'user_id' => Auth::user()->id,
        ]);
    }
}
