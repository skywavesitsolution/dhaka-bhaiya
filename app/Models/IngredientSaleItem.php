<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientSaleItem extends Model
{
    protected $fillable = ['ingredient_sale_id', 'ingredient_id', 'qty'];

    /**
     * An ingredient sale item belongs to an ingredient sale.
     */
    public function ingredientSale()
    {
        return $this->belongsTo(IngredientSale::class);
    }

    /**
     * An ingredient sale item belongs to a product variant (ingredient).
     */
    public function ingredient()
    {
        return $this->belongsTo(ProductVariant::class, 'ingredient_id');
    }
}