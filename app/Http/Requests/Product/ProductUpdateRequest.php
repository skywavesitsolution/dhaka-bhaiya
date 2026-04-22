<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_variant_id' => 'required|exists:product_variants,id',
            'code' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'product_urdu_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'is_manage_variants' => 'nullable|boolean',
            'is_fixed_asset' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'new_arrival' => 'nullable|boolean',
            'best_selling_product' => 'nullable|boolean',
            'service_item' => 'nullable|boolean',
            'finish_goods' => 'nullable|boolean',
            'raw_material' => 'nullable|boolean',
            'manage_deal_id' => 'nullable|boolean',
            'low_stock' => 'nullable|integer|min:0',
            'retail_price' => 'required|numeric|min:0',
            'measuring_unit_id' => 'required|exists:measuring_units,id',
            'min_order_qty' => 'required|integer|min:1',
            'product_description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ];
    }
}
