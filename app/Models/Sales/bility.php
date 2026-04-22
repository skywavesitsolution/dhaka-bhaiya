<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bility extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sale_id',
        'bilty_number',
        'number_of_corton',
        'cargo_name',
        'vahical_number',
        'bilty_date',
        'remarks',
       
    ];
}
