<?php

namespace App\Models\Sales;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Location\ProductLocation;
use App\Models\Product\Variant\ProductVariantRate;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'invoice_id',
        'product_id',
        'retail_price',
        'sale_qty',
        'sale_discount_type',
        'sale_discount_value',
        'sale_discount_actual_value',
        'sale_amount',
        'location',
        'product_location_id',
        'gst_type',
        'gst_percentage',
        'gst_amount',
        'scheme_qty',
        'remarks',
        'ip_address',
    ];

    public function getProduct()
    {
        return $this->belongsTo(ProductVariant::class, 'product_id');
    }

    public function Rates()
    {
        return $this->belongsTo(ProductVariantRate::class, 'product_id');
    }

    public function invoice()
    {
        return $this->belongsTo(SaleInvoice::class, 'invoice_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function productLocation()
    {
        return $this->belongsTo(ProductLocation::class, 'product_location_id');
    }
}
