<?php

namespace App\Models;

use App\Models\Purchase;
use App\Models\Sales\SaleInvoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartyLedger extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'updated_id',
        'party_id',
        'party_type',
        'payment',
        'received',
        'price',
        'balance',
        'sale_id',
        'purchase_id',
        'payment_id',
        'recevied_id',
        'order_id',
        'remarks',
        'user_id'
    ];

    public function sale()
    {
        return $this->belongsTo(SaleInvoice::class, 'sale_id');
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}
