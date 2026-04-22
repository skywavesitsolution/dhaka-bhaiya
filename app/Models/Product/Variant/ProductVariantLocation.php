<?php

namespace App\Models\Product\Variant;

use App\Models\Product\Location\ProductLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantLocation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_variant_id',
        'location_id',
        'stock_qty',
        'user_id',
    ];

    public function ProductVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id', 'id');
    }

    public function ProductLocation()
    {
        return $this->belongsTo(ProductLocation::class, 'location_id', 'id');
    }
}
