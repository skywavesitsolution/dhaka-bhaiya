<?php

namespace App\Models\Product\MeasuringUnit;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Variant\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeasuringUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'symbol',
        'quantity',
        'description',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'measuring_unit_id');
    }
}
