<?php

namespace App\Actions;

use App\Models\Party;

class UpdatePartyBalance
{
    public function execute(int $partyId, float $price, string $type)
    {
        $party = Party::find($partyId);
        $party->updateBalance($price, $type);
    }
}
