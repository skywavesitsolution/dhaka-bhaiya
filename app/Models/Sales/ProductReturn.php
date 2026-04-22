<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'product_id',
        // 'product_name',
        'retail_price',
        'return_qty',
        // 'product_discount_type',
        // 'product_discount_value',
        'return_dicount',
        'return_amount',
        'ip_address',
    ];
}
