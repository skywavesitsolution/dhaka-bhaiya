<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvanceIssue extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'advance_amount',
        'return_type',
        'no_of_installment',
        'return_date',
        'payemnt_from_account',
    ];
}
