<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceivedPaymentItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'received_payment_id',
        'particular',
        'particular_id',
        'particular_name',
        'payment',
        'remarks',
    ];

    public function receivedPayment()
    {
        return $this->belongsTo(\App\Models\Account\ReceivedPayment::class, 'received_payment_id');
    }

    public function paymentParticular()
    {
        if ($this->particular == 'Account') {
            return $this->belongsTo(\App\Models\Account\Account::class, 'particular_id');
        }

        return $this->belongsTo(\App\Models\Party::class, 'particular_id');
    }
}
