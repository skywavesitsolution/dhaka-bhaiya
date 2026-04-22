<?php

namespace App\Models\Sales;

use App\Models\Table\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KitchenInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'quotation_invoice_id',
        'party_id',
        'customer_reciveable',
        'customer_name',
        'customer_number',
        'bill_date',
        'total_bill',
        'payment_type',
        'discount_type',
        'discount_value',
        'discount_actual_value',
        'table_number',
        'employee',
        'order_type',
        'adjustment',
        'net_payable',
        'ip_address',
        'updated_by',
        'status',
    ];


    function Products()
    {
        return $this->hasMany(ProductHold::class);
    }

    public function kitchenInvoiceDetails()
    {
        return $this->hasMany(KitchenInvoiceDetail::class, 'kitchen_invoice_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_number');
    }

    public function invoiceQuotation()
    {
        return $this->invoiceQuotation(quotationInvoice::class, 'quotation_invoice_id');
    }
}
