<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHoldInvoice extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'party_id',
        'customer_reciveable',
        'bill_date',
        'payment_type',
        'total_bill',
        'discount_type',
        'discount_value',
        'discount_actual_value',
        'adjustment',
        'net_payable',
        'employee_id',
        'ip_address',
    ];


    function Products()
    {
        return $this->hasMany(ProductHold::class);
    }
}
