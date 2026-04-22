<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'balance',
        'opening_balance',
        'account_number',
        'user_id'
    ];

    public function updateBalance(float $payment, string $type)
    {
        if ($type === 'increment') {
            $this->balance += $payment;
        } elseif ($type === 'decrement') {
            $this->balance -= $payment;
        }

        $this->save();
    }
}
