<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenseSubCategory extends Model
{
    use HasFactory;

    protected $fillable = ['exp_sub_category', 'category_id'];

    public function categoryOf()
    {
        return $this->belongsTo(\App\Models\Account\expenseCategory::class, 'category_id');
    }
}
