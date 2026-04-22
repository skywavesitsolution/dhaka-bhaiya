<?php

namespace App\Models\Sales;

use App\Models\Party;
use App\Models\SaleBatch;
use App\Models\ProductType;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'party_id',
        'customer_reciveable',
        'bill_date',
        'due_date',
        'payment_type',
        'received_amount',
        'total_bill',
        'discount_type',
        'discount_value',
        'discount_actual_value',
        'service_charges',
        'adjustment',
        'net_payable',
        'total_gst',
        'total_scheme_qty',
        'employee_id',
        'sale_batch_id',
        'account_id',
        'status',
        'order_type',
        'ip_address',
    ];

    public function party()
    {
        return $this->belongsTo(Party::class, 'party_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    function getProduct()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function saleProduct()
    {
        return $this->hasMany(SaleProduct::class, 'invoice_id');
    }
    public function bilty()
    {
        return $this->hasOne(bility::class, 'sale_id');
    }

    public function receving()
    {
        return $this->hasOne(bility::class, 'sale_id');
    }

    public function saleBatch()
    {
        return $this->belongsTo(SaleBatch::class, 'sale_batch_id');
    }
}
