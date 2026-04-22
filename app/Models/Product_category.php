<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_category extends Model
{
    use HasFactory;

    protected $fillable = ['category_name'];



    public function productTypes()
    {
        return $this->hasMany(ProductType::class, 'category_id');
    }
}
