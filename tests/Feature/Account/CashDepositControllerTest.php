<?php

namespace Tests\Feature\Account;

use App\Models\Account\Account;
use App\Models\Account\CashDeposit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CashDepositControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testAddCashDeposit(): void
    {
        $user = User::factory()->create();
        $account = Account::factory()->create();

        $depositAmount = fake()->randomNumber(3);
        $payload = [
            'depositAmount' => $depositAmount,
            'depositBy' => fake()->name(),
            'accountId' => $account->id,
        ];

        $this->actingAs($user)->post('/add-cash-deposit', $payload);

        $this->assertDatabaseHas('cash_deposits', [
            'deposit_amount' => $payload['depositAmount'],
            'deposit_by' => $payload['depositBy'],
            'account_id' => $payload['accountId'],
            'user_id' => Auth::user()->id,
        ]);

        $this->assertDatabaseHas('accounts', [
            'id' => $payload['accountId'],
            'balance' => $payload['depositAmount'] + $account->balance,
        ]);

        $cashDeposit = CashDeposit::first();
        $this->assertDatabaseHas('account_ledgers', [
            'deposit_id' => $cashDeposit->id,
            'account_id' => $payload['accountId'],
            'received' => $payload['depositAmount'],
            'balance' => $payload['depositAmount'] + $account->balance,
        ]);
    }
}
