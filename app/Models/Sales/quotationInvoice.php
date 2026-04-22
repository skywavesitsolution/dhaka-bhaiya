<?php

namespace App\Models\Sales;

use App\Models\Table\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class quotationInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'party_id',
        'customer_reciveable',
        'customer_name',
        'customer_number',
        'customer_address',
        'status',
        'bill_date',
        'payment_type',
        'total_bill',
        'discount_type',
        'discount_value',
        'discount_actual_value',
        'adjustment',
        'table_number',
        'employee',
        'order_type',
        'net_payable',
        'ip_address',
    ];


    function Products()
    {
        return $this->hasMany(ProductHold::class);
    }



    public function qutaionProduct()
    {
        return $this->hasMany(quotationInvoiceDetail::class, 'invoice_quotation_id');
    }

    public function table()
    {
        return $this->belongsTo(Table::class, 'table_number');
    }
}
