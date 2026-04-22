<?php

namespace App\Models\Product\Variant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductVariantLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'updated_id',
        'product_variant_id',
        'product_variant_order_id',
        'product_variant_sale_id',
        'product_variant_purchase_id',
        'product_variant_order_stock',
        'product_variant_sale_stock',
        'product_variant_purchase_stock',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }
}
