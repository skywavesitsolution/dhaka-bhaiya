<?php

namespace Tests\Feature\Account;

use App\Models\Account\Account;
use App\Models\Account\MakePayment;
use App\Models\Account\MakePaymentItems;
use App\Models\Party;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class MakePaymentControllerTest extends TestCase
{
    use RefreshDatabase;
    public function testMakePayment(): void
    {
        $user = User::factory()->create();
        $party = Party::factory(2)->create();
        $account = Account::factory(2)->create();

        $payments = [fake()->numberBetween(1, 1000), fake()->numberBetween(1, 1000), fake()->numberBetween(1, 1000)];

        $totalPayments = $payments[0] + $payments[1] + $payments[2];
        $payload = [
            'date' => fake()->date,
            'previousBalance' => $account[0]->balance,
            'updatedBalance' => $account[0]->balance - $totalPayments,
            'totalPayments' => $totalPayments,
            'accountId' => $account[0]->id,
            'particular' => ['Party', 'Party', 'Account'],
            'particularId' => [$party[0]->id, $party[1]->id, $account[1]->id],
            'particularName' => [$party[0]->name, $party[1]->name, $account[1]->account_name],
            'payment' => $payments,
            'remarks' => [fake()->text(20), fake()->text(20), fake()->text(20)],

        ];



        $response = $this->actingAs($user)->post('add-make-payment', $payload);

        $this->assertDatabaseHas('make_payments', [
            'date' => $payload['date'],
            'prev_balance' => $payload['previousBalance'],
            'updated_balance' => $payload['updatedBalance'],
            'total_payments' => $payload['totalPayments'],
            'account_id' => $payload['accountId'],
            'user_id' => Auth::user()->id,
        ]);

        $makePayment = MakePayment::first();
        $this->assertDatabaseHas('make_payment_items', [
            'make_payment_id' => $makePayment->id,
            'particular' => $payload['particular'][0],
            'particular_name' => $payload['particularName'][0],
            'particular_id' => $payload['particularId'][0],
            'payment' => $payload['payment'][0],
        ]);

        $this->assertDatabaseHas('parties', [
            'id' => $payload['particularId'][0],
            'balance' => $party[0]->balance - $payload['payment'][0],
        ]);

        $makePaymentItems = MakePaymentItems::first();
        $this->assertDatabaseHas('party_ledgers', [
            'payment_id' => $makePaymentItems->id,
            'party_id' => $payload['particularId'][0],
            'received' => $payload['payment'][0],
            'balance' => $party[0]->balance - $payload['payment'][0],
        ]);

        // Main Account Assertion
        $this->assertDatabaseHas('accounts', [
            'id' => $payload['accountId'],
            'balance' => $account[0]->balance - $payload['totalPayments'],
        ]);

        $this->assertDatabaseHas('account_ledgers', [
            'payment_id' => $makePayment->id,
            'payment' => $payload['totalPayments'],
            'balance' => $account[0]->balance - $payload['totalPayments'],
            'account_id' => $payload['accountId'],
        ]);

        // Payment Item Account Assertion
        $this->assertDatabaseHas('accounts', [
            'id' => $payload['particularId'][2],
            'balance' => $account[1]->balance + $payload['payment'][2],
        ]);

        $makePaymentItems = MakePaymentItems::OrderBy('id', 'desc')->first();
        $this->assertDatabaseHas('account_ledgers', [
            'sub_payment_id' => $makePaymentItems->id,
            'received' => $payload['payment'][2],
            'balance' => $account[1]->balance + $payload['payment'][2],
            'account_id' => $payload['particularId'][2],
        ]);
    }

    public function testMakePaymentItemDelete()
    {
        $user = User::factory()->create();
        $party = Party::factory(2)->create();
        $account = Account::factory(2)->create();

        $payments = [fake()->numberBetween(1, 1000), fake()->numberBetween(1, 1000), fake()->numberBetween(1, 1000)];

        $totalPayments = $payments[0] + $payments[1] + $payments[2];
        $payload = [
            'date' => fake()->date,
            'previousBalance' => $account[0]->balance,
            'updatedBalance' => $account[0]->balance - $totalPayments,
            'totalPayments' => $totalPayments,
            'accountId' => $account[0]->id,
            'particular' => ['Party', 'Party', 'Account'],
            'particularId' => [$party[0]->id, $party[1]->id, $account[1]->id],
            'particularName' => [$party[0]->name, $party[1]->name, $account[1]->account_name],
            'payment' => $payments,
            'remarks' => [fake()->text(20), fake()->text(20), fake()->text(20)],

        ];



        $response = $this->actingAs($user)->post('add-make-payment', $payload);

        $this->assertDatabaseHas('make_payments', [
            'date' => $payload['date'],
            'prev_balance' => $payload['previousBalance'],
            'updated_balance' => $payload['updatedBalance'],
            'total_payments' => $payload['totalPayments'],
            'account_id' => $payload['accountId'],
            'user_id' => Auth::user()->id,
        ]);

        $makePayment = MakePayment::first();
        $this->assertDatabaseHas('make_payment_items', [
            'make_payment_id' => $makePayment->id,
            'particular' => $payload['particular'][0],
            'particular_name' => $payload['particularName'][0],
            'particular_id' => $payload['particularId'][0],
            'payment' => $payload['payment'][0],
        ]);

        $this->assertDatabaseHas('parties', [
            'id' => $payload['particularId'][0],
            'balance' => $party[0]->balance - $payload['payment'][0],
        ]);

        $makePaymentItems = MakePaymentItems::first();
        $this->assertDatabaseHas('party_ledgers', [
            'payment_id' => $makePaymentItems->id,
            'party_id' => $payload['particularId'][0],
            'received' => $payload['payment'][0],
            'balance' => $party[0]->balance - $payload['payment'][0],
        ]);

        // Main Account Assertion
        $this->assertDatabaseHas('accounts', [
            'id' => $payload['accountId'],
            'balance' => $account[0]->balance - $payload['totalPayments'],
        ]);

        $this->assertDatabaseHas('account_ledgers', [
            'payment_id' => $makePayment->id,
            'payment' => $payload['totalPayments'],
            'balance' => $account[0]->balance - $payload['totalPayments'],
            'account_id' => $payload['accountId'],
        ]);

        // Payment Item Account Assertion
        $this->assertDatabaseHas('accounts', [
            'id' => $payload['particularId'][2],
            'balance' => $account[1]->balance + $payload['payment'][2],
        ]);

        $makePaymentItems = MakePaymentItems::OrderBy('id', 'desc')->first();
        $this->assertDatabaseHas('account_ledgers', [
            'sub_payment_id' => $makePaymentItems->id,
            'received' => $payload['payment'][2],
            'balance' => $account[1]->balance + $payload['payment'][2],
            'account_id' => $payload['particularId'][2],
        ]);
    }
}
