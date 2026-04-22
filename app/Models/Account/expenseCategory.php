<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenseCategory extends Model
{
    use HasFactory;

    public function categoryExpense()
    {
        return $this->hasMany(\App\Models\Account\expense::class, 'category_id');
    }
}
