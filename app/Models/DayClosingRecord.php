<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayClosingRecord extends Model {

    use HasFactory;
    protected $fillable = [
        'total_expense',
        'net_payable',
        'received_amount',
        'cash_sale',
        'nozzale_total',
        'grand_total',
        'closing_date',
    ];
}

