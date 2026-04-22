<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashDeposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposit_amount',
        'deposit_by',
        'account_id',
        'user_id'
    ];
    public function accounts()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
