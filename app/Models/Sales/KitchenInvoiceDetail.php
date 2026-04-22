<?php

namespace App\Models\Sales;

use App\Models\Deals\DealTable;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Variant\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KitchenInvoiceDetail extends Model
{

    use HasFactory;

    protected $fillable = [
        'user_id',
        'kitchen_invoice_id',
        'quotation_invoice_detail_id',
        'product_id',
        'retail_price',
        'sale_qty',
        'dicount_type',
        'dicount_value',
        'sale_amount',
        'ip_address',
        'location',
        'remarks',
        'sent_to_kitchen',
    ];


    function Products()
    {
        return $this->belongsTo(ProductHoldInvoice::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductVariant::class, 'product_id');
    }

    public function kitchenInvoice()
    {
        return $this->belongsTo(KitchenInvoice::class, 'kitchen_invoice_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_id');
    }


    public function deal()
    {
        return $this->belongsTo(DealTable::class, 'product_id', 'product_variant_deal_id');
    }

    public function quotationInvoiceDetail()
    {
        return $this->belongsTo(quotationInvoiceDetail::class, 'quotation_invoice_detail_id');
    }
}
