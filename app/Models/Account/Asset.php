<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $fillable = [
        'deposite_id',
        'withdrawal_id',
        'assets_value',
        'user_id',
        
    ];
    
}
