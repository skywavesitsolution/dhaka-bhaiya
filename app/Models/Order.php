<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'marka_id',
        'product_type',
        'purchase_qty',
        'purchase_rate',
        'total_purchase',
        'driver_id',
        'carriage_amount',
        'total_carriage',
        'grand_purchase_amount',
        'supplier_id',
        'customer_id',
        'sale_rate',
        'total_sale_amount',
        'profit',
        'remarks',
    ];

    public function product()
    {
        return $this->BelongsTo(\App\Models\ProductType::class, 'product_type');
    }

    public function maraka()
    {
        return $this->BelongsTo(\App\Models\Party::class, 'marka_id');
    }
    public function driver()
    {
        return $this->BelongsTo(\App\Models\Party::class, 'driver_id');
    }

    public function customer()
    {
        return $this->BelongsTo(\App\Models\Party::class, 'customer_id');
    }

    public function supplier()
    {
        return $this->BelongsTo(\App\Models\Supplier::class, 'supplier_id');
    }
}
