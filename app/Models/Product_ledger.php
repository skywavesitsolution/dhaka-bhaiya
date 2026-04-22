<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'updated_id',
        'product_id',
        'sale_id',
        'purchase_id',
        'sale_stock',
        'purchase_stock',
        'closing_reading',
    ];
    public function product()
    {
        return $this->belongsTo(ProductType::class, 'product_id');
    }
}
