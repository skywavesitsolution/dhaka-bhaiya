<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartyDiscountProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_variant_id',
        'cost_price',
        'retail_price',
        'product_discount',
        'product_discount_type',
        'party_id',
        'user_id',
    ];
}
