<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductVariantCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id',
            'measuring_unit_id' => 'required|exists:measuring_units,id',
            'location_id' => 'nullable|exists:product_locations,id',
            'size_id' => 'nullable|exists:product_sizes,id',
            'opening_stock' => 'nullable|string',
            'low_stock' => 'nullable|string',
            'inner_pack' => 'nullable|string',
            'loose_pack' => 'nullable|string',
            'cost_price' => 'required|string',
            'wholesale_price' => 'nullable|string',
            'retail_price' => 'required|string',
            'min_order_qty' => 'nullable|string',
            'image' => 'image|mimes:jpg,jpeg,png,gif|max:2048',
            'variant_description' => 'nullable|string|max:1000',
        ];
    }
}
