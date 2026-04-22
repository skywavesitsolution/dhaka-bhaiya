<?php

namespace App\Models\Table;

use App\Models\Product\Location\ProductLocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;


    protected $fillable = [
        'table_number',
        'table_location',
        'status',
        'user_id',
    ];


    public function location()
    {
        return $this->belongsTo(ProductLocation::class, 'table_location');
    }
}
