<?php

namespace App\Models\StockManagment;

use App\Models\Product\Location\ProductLocation;
use App\Models\Product\Variant\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransferDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'stock_transfer_id',
        'product_variant_id',
        'to_location_id',
        'stock_at_time_of_transfer',
        'qty',
    ];

    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class, 'stock_transfer_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(ProductLocation::class, 'to_location_id');
    }
}
