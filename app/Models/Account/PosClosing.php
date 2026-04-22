<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosClosing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_date',
        'cash_submited',
        'system_cash',
        'customer_payments',
        'total_cash',
        'phisical_cash',
        'differ_cash',

    ];
}
