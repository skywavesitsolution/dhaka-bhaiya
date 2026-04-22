<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expense extends Model
{
    use HasFactory;

    public function expenseAccount()
    {
        return $this->belongsTo(\App\Models\Account\Account::class, 'account_id');
    }

    public function expenseCategory()
    {
        return $this->belongsTo(\App\Models\Account\expenseCategory::class, 'category_id');
    }

    public function expenseSubCategory()
    {
        return $this->belongsTo(\App\Models\Account\expenseSubCategory::class, 'sub_category_id');
    }
}
