<?php

namespace App\Models\StockManagment;

use App\Enums\StockTransferTypeEnum;
use App\Models\Product\Location\ProductLocation;
use App\Models\Product\Variant\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockLedger extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'product_variant_id',
        'location_id',
        'stock_after_transaction',
        'qty',
        'transfer_type',
        'user_id',
    ];

    protected $casts = [
        'transfer_type' => StockTransferTypeEnum::class,
    ];

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function location()
    {
        return $this->belongsTo(ProductLocation::class, 'location_id');
    }

    public function getStockTransferTypeAttribute($value)
    {
        return StockTransferTypeEnum::from($value);
    }
}
