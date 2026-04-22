<?php

namespace App\Models;

use App\Models\Sales\SaleInvoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', // ULID as primary key
        'status',
    ];

    public $incrementing = false; // Because ULID is used as primary key
    protected $keyType = 'string'; // ULID is stored as a string

    public function saleInvoices()
    {
        return $this->hasMany(SaleInvoice::class, 'sale_batch_id');
    }
}
