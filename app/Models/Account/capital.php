<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class capital extends Model
{
    use HasFactory;

    protected $fillable = [
        'deposite_id',
        'withdrawal_id',
        'current_capital',
        'user_id'
    ];

    public function account()
    {
        return $this->belongsTo(\App\Models\Account\Account::class, 'account_id');
    }
    
}
