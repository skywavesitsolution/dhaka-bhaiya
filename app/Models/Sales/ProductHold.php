<?php

namespace App\Models\Sales;

use App\Models\Product\Variant\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductHold extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_hold_id',
        'product_id',
        // 'product_name',
        'retail_price',
        'sale_qty',
        // 'product_discount_type',
        // 'product_discount_value',
        'dicount_type',
        'dicount_value',
        'sale_amount',
        'location',
        'remarks',
        'ip_address',
    ];


    function Products()
    {
        return $this->belongsTo(ProductHoldInvoice::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_id');
    }
}
