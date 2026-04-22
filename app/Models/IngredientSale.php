<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IngredientSale extends Model
{
    protected $fillable = ['sale_id', 'product_id'];

    /**
     * An ingredient sale belongs to a sale.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * An ingredient sale belongs to a product variant (service item).
     */
    public function product()
    {
        return $this->belongsTo(ProductVariant::class, 'product_id');
    }

    /**
     * An ingredient sale has many ingredient sale items.
     */
    public function items()
    {
        return $this->hasMany(IngredientSaleItem::class, 'ingredient_sale_id');
    }
}