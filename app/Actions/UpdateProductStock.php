<?php

namespace App\Actions;

use Exception;
use App\Models\Product_stock;

class UpdateProductStock
{
    public function execute($productID, $consumptionQty)
    {
        $productStock = Product_stock::where('product_id', $productID)->first();
        if ($productStock && $productStock->stock >= $consumptionQty) {
            $productStock->stock -= $consumptionQty;
            $productStock->save();
        } else {
            throw new Exception("Not enough stock for Product ID {$productID}.");
        }
    }
}
