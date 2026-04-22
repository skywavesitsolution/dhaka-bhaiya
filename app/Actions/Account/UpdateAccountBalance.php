<?php

namespace App\Actions\Account;

use App\Models\Account\Account;

class UpdateAccountBalance
{
    public function execute(int $accountId, float $amount, string $type)
    {
        $account = Account::find($accountId);
        $account->updateBalance($amount, $type);
    }
}
