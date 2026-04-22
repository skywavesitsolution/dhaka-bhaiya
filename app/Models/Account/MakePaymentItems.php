<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MakePaymentItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'make_payment_id',
        'particular',
        'particular_id',
        'particular_name',
        'payment',
        'remarks',
    ];

    public function makePayment()
    {
        return $this->belongsTo(\App\Models\Account\MakePayment::class, 'make_payment_id');
    }

    public function paymentParticular()
    {
        if ($this->particular == 'Account') {
            return $this->belongsTo(\App\Models\Account\Account::class, 'particular_id');
        }

        return $this->belongsTo(\App\Models\Party::class, 'particular_id');
    }
}
