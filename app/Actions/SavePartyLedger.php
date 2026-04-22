<?php

namespace App\Actions;

use App\Models\Party;
use App\Models\PartyLedger;
use Illuminate\Support\Facades\Auth;

class SavePartyLedger
{
    public function execute(int $partyId, float $price, string $feildName, $paymentFeildName, int $feildId, $remarks = null, $date = null)
    {
        $party = Party::find($partyId);
        PartyLedger::create([
            'date' => $date,
            'party_id' => $party->id,
            'party_type' => $party->type,
            "{$paymentFeildName}" => $price,
            'balance' => $party->balance,
            "{$feildName}" => $feildId,
            'user_id' => Auth::user()->id,
            'remarks' => $remarks
        ]);
    }
}
