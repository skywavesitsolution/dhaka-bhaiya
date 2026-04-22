<?php

namespace App\Models\Payroll;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'opening_balance',
        'balance',
        'opening_advance_balnce',
        'advance_balnce',
        'basic_salary',
        'email',
        'phone',
        'joining_date',
        'address',
        'user_id',
    ];
}
