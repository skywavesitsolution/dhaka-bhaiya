<?php

namespace App\Models;

use App\Models\Product\MeasuringUnit\MeasuringUnit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'name',
        'category_id',
        'measuring_unit_id',
        'opening_stock',
        'user_id',
        'ip_address',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function productRates()
    {
        return $this->hasMany(Product_rate::class, 'product_id');
    }

    public function category()
    {
        return $this->belongsTo(Product_category::class, 'category_id');
    }

    public function measuringUnit()
    {
        return $this->belongsTo(MeasuringUnit::class, 'measuring_unit_id');
    }
}
