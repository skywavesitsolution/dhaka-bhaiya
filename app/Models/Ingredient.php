<?php

namespace App\Models;
use App\Models\Product\Variant\ProductVariant;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['product_variant_id', 'ingredient_total_cost'];

    /**
     * A recipe belongs to one service item (product variant).
     */
    public function serviceItem()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    /**
     * A recipe has many ingredient items.
     */
    public function items()
    {
        return $this->hasMany(IngredientsItem::class, 'ingredients_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}