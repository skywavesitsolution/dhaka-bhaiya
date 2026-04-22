<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
        $isManageVariants = $this->input('is_manage_variants');
        $isServiceItem = $this->input('service_item', 0);
        $isFinishGoods = $this->input('finish_goods', 0);
        $isRawMaterial = $this->input('raw_material', 0);
    
        $rules = [
            'code' => 'required|string|max:255',
            'product_name' => 'required|string|max:255',
            'product_urdu_name' => 'nullable|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'brand_id' => 'nullable|exists:product_brands,id',
            'supplier_id' => 'nullable|exists:parties,id',
            'product_description' => 'nullable|string',
            'is_featured' => 'required|boolean',
            'is_manage_variants' => 'required|boolean',
            'new_arrival' => 'required|boolean',
            'best_selling_product' => 'required|boolean',
            'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    
        if ($isManageVariants == '0') {
            $rules['measuring_unit_id'] = 'required|exists:measuring_units,id';
            $rules['min_order_qty'] = 'required|numeric|min:1';
            $rules['manage_deal_id'] = 'nullable|boolean';
    
            // Only require these fields if it's not a service item, finish good, or raw material
            if (!$isServiceItem && !$isFinishGoods && !$isRawMaterial) {
                $rules['opening_stock'] = 'nullable|numeric|min:0';
                $rules['low_stock'] = 'nullable|numeric|min:0';
                $rules['inner_pack'] = 'nullable|numeric|min:1';
                $rules['loose_pack'] = 'nullable|numeric|min:1';
                $rules['cost_price'] = 'required|numeric|min:0';
                $rules['retail_price'] = 'required|numeric|min:0';
                $rules['product_location_id'] = 'required|exists:product_locations,id';
            }
    
            if ($this->hasFile('image')) {
                $rules['image'] = 'image|mimes:jpg,jpeg,png,gif|max:2048';
            }
        }
    
        return $rules;
    }
}
