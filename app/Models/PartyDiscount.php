<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyDiscount extends Model
{
    use HasFactory;

    protected $fillable = [
        'party_id',
        'discount_type',
        'discount_value',
        'user_id',
    ];
}
