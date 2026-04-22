<?php

namespace App\Models;

use App\Models\Account\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'received_date',
        'due_date',
        'status',
        'supplier_id',
        'supplier_balance',
        'payment_type',
        'payment_amount',
        'account_id',
        'total_bill',
        'adjustment',
        'net_payable',
    ];

    public function purchase_details()
    {
        return $this->hasMany(Purchase_detail::class, 'purchase_id');
    }
    

    public function supplier()
    {
        return $this->belongsTo(Party::class);
    }
    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
