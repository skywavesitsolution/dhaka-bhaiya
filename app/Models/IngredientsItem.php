<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Variant\ProductVariant;

class IngredientsItem extends Model
{
    protected $fillable = ['ingredients_id', 'product_variant_id', 'qty'];

    /**
     * An ingredient item belongs to a recipe.
     */
    public function recipe()
    {
        return $this->belongsTo(Ingredient::class, 'ingredients_id');
    }

    /**
     * An ingredient item belongs to a product variant (ingredient).
     */
    public function ingredient()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
}