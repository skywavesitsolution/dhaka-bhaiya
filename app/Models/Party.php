<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Party extends Model
{
    use HasFactory;

    protected $attributes = [
        'supplier_id' => 1,
    ];

    protected $fillable = [
        'name',
        'type',
        'supplier_id' => 1,
        'opening_balance',
        'balance',
        'email',
        'company_name',
        'address',
        'phone_number',
        'user_id',
    ];

    public function updateBalance(float $price, string $type)
    {
        if ($type == 'increment') {
            $this->balance += $price;
        } elseif ($type == 'decrement') {
            $this->balance -= $price;
        }

        $this->save();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function customerDiscount()
    {
        return $this->hasOne(PartyDiscount::class);
    }
    // public function purchase()
    // {
    //     return $this->hasMany(Purchase::class);
    // }
}
