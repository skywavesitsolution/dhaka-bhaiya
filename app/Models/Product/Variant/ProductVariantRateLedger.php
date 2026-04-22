<?php

namespace App\Models\Product\Variant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantRateLedger extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_variant_id',
        'old_retail_price',
        'new_retail_price',
        'change_date',
        'user_id',
    ];
}
