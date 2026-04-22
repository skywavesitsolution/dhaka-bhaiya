<?php

namespace App\Models\Deals;

use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Variant\ProductVariantLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id',
        'product_variant_id',
        'product_variant_qty',
        'retail_price',
        'total_price',
        'user_id',
    ];


    public function products()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function productVariantLocation()
    {
        return $this->hasMany(ProductVariantLocation::class, 'product_variant_id');
    }
}
