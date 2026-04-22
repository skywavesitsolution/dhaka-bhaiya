<?php

namespace App\Models\Deals;

use App\Models\Product\Product;
use App\Models\Product\Variant\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealTable extends Model
{
    use HasFactory;


    protected $fillable = [
        'product_variant_deal_id',
        'deal_total',
        'start_date',
        'end_date',
        'status',
        'user_id'
    ];

    public function deal_item()
    {
        return $this->hasMany(DealItem::class, 'deal_id');
    }
    public function products()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_deal_id');
    }
}
