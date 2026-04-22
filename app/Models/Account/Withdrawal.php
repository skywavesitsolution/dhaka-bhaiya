<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;


    protected $fillable = [
        'account_id',
        'account_id_to',
        'withdrawal_amount',
        'remarks',
        'user_id'
    ];

    public function account()
    {
        return $this->belongsTo(\App\Models\Account\Account::class, 'account_id');
    }

    public function capital()
    {
        return $this->hasMany(capital::class, 'withdrawal_id');
    }
}
