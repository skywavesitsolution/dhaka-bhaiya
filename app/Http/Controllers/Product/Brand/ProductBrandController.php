<?php

namespace App\Http\Controllers\Product\Brand;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\Brand\ProductBrand;
use App\Models\Product\Category\ProductCategory;
use App\Models\Product\Product;

class ProductBrandController extends Controller
{
    public function index()
    {
        $productBrands = ProductBrand::with('user')->paginate(10);
        return view(
            'adminPanel.product.brand.productBrandList',
            [
                'productBrands' => $productBrands,
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string']
        ]);

        $result = ProductBrand::create([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Product Brand added successfully']);
        }
        return redirect()->back()->with(['error' => 'Something went wrong try again']);
    }

    public function show(string $id)
    {
        $productBrand = ProductBrand::find($id);

        return response()->json(['data' => $productBrand]);
    }

    public function update(Request $request)
    {
        $productBrand = ProductBrand::find($request->productBrandId);
        $result = $productBrand->update([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Product Brand Updated Successfully']);
        }

        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }

    public function destroy($id)
    {
        $productBrand = ProductBrand::find($id);

        if (!$productBrand) {
            return response()->json(['message' => 'Product Brand not found'], 404);
        }

        $productExists = Product::where('brand_id', $productBrand->id)->exists();

        if ($productExists) {
            return response()->json(['message' => 'This brand is assigned to one or more products and cannot be deleted.'], 400);
        }

        $productBrand->delete();

        return response()->json(['message' => 'Brand deleted successfully.'], 200);
    }
}
