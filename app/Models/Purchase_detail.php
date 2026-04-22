<?php

namespace App\Models;

use App\Models\Product\Variant\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase_detail extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'product_variant_id',
        'stock',
        'cost_price',
        'retail_price',
        'wholesale_price',
        'qty',
        'total',
        'location',
        'discount_type',
        'discount_value',
        'actual_discount_value',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductType::class, 'product_id');
    }

    public function productVarient()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}
