<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetsDeposite extends Model
{
    use HasFactory;

    protected $fillable = [
        'assest_name',
        'deposite_value',
        'date',
        'time',
        'user_id'
    ];


    public function assets()
    {
        return $this->hasMany(assets::class, 'deposite_id');
    }
}
