<?php

namespace App\Actions\Account;

use App\Models\Account\Account;
use App\Models\Account\AccountLedger;
use Illuminate\Support\Facades\Auth;

class SaveAccountLedger {
    public function execute( int $accountId, float $amount, string $paymentFeildName, string $feildName, int $feildId, $remarks = null, $date = null ) {
        // dd( $accountId );
        $account = Account::find( $accountId );
        AccountLedger::create( [
            'date' => $date,
            'account_id' => $account->id,
            "{$paymentFeildName}" => $amount,
            'balance' => $account->balance,
            "{$feildName}" => $feildId,
            'user_id' => Auth::user()->id,
            'remarks' => $remarks
        ] );
    }
}
