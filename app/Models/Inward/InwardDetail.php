<?php

namespace App\Models\Inward;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InwardDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'inward_id',
        'product_name',
        'qty',
    ];

    public function inward()
    {
        return $this->belongsTo(Inward::class, 'inward_id', 'id');
    }
}
