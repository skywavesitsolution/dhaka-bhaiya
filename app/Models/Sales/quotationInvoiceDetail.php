<?php

namespace App\Models\Sales;

use App\Models\Deals\DealTable;
use App\Models\Product\Variant\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quotationInvoiceDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'invoice_quotation_id',
        'product_id',
        'product_name',
        'retail_price',
        'sale_qty',
        'dicount_type',
        'dicount_value',
        'sale_dicount',
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


    public function deal()
    {
        return $this->belongsTo(DealTable::class, 'product_id', 'product_variant_deal_id');
    }
}
