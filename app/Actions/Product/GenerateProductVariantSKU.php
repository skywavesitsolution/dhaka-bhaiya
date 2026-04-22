<?php

namespace App\Actions\Product;

use App\Models\Product\Category\ProductCategory;

class GenerateProductVariantSKU
{
    public function execute(ProductCategory $category, $size = null, $color = null, $productCode)
    {
        $categoryCode = strtoupper(substr($category->name, 0, 3));

        $skuParts = [$categoryCode];

        if ($size) {
            $sizeCode = strtoupper(substr($size, 0, 1));
            $skuParts[] = $sizeCode;
        }

        if ($color) {
            $colorCode = strtoupper(substr($color, 0, 2));
            $skuParts[] = $colorCode;
        }

        $skuParts[] = strtoupper($productCode);

        $sku = implode('-', $skuParts);

        return $sku;
    }
}
