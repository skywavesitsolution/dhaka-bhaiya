<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dayClosing extends Model
{
    use HasFactory;

   protected $fillable = [
    'date',
    'user_id ',
    'opening_balance',
    'physical_balance',
    'expence',
    'closing_balance',
   ];
}
