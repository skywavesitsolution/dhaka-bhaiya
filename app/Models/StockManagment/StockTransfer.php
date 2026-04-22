<?php

namespace App\Models\StockManagment;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Location\ProductLocation;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransfer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'transaction_id',
        'date',
        'time',
        'from_location_id',
        'qty',
        'user_id',
    ];

    public function fromLocation()
    {
        return $this->belongsTo(ProductLocation::class, 'from_location_id');
    }

    public function details()
    {
        return $this->hasMany(StockTransferDetail::class, 'stock_transfer_id');
    }
}
