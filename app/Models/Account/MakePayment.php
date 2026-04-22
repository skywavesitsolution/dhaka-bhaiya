<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MakePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'prev_balance',
        'updated_balance',
        'total_payments',
        'account_id',
        'user_id',
    ];

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => date('d-m-Y', strtotime($value)),
        );
    }

    public function account()
    {
        return $this->belongsTo(\App\Models\Account\Account::class, 'account_id');
    }

    public function paymentItems()
    {
        return $this->hasMany(\App\Models\Account\MakePaymentItems::class, 'make_payment_id');
    }
}
