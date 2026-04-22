<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_rate extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'cost_price',
        'retail_price',
        'user_id',
        'ip_address',
    ];

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_id');
    }
}
