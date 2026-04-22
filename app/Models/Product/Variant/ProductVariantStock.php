<?php

namespace App\Models\Product\Variant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVariantStock extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_variant_id',
        'opening_stock',
        'stock',
        'low_stock',
        'inner_pack',
        'loose_pack',
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
