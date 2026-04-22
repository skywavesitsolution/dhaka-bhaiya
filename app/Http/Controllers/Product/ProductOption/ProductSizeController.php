<?php

namespace App\Http\Controllers\Product\ProductOption;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\ProductOption\ProductSize;
use App\Models\Product\Variant\ProductVariant;

class ProductSizeController extends Controller
{
    public function index()
    {
        $productSizes = ProductSize::with('user')->paginate(10);
        return view(
            'adminPanel.product.productOption.size.productSizeList',
            [
                'productSizes' => $productSizes,
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string']
        ]);

        $result = ProductSize::create([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Product Size added successfully']);
        }
        return redirect()->back()->with(['error' => 'Something went wrong try again']);
    }

    public function show(string $id)
    {
        $productSize = ProductSize::find($id);

        return response()->json(['data' => $productSize]);
    }

    public function update(Request $request)
    {
        $productSize = ProductSize::find($request->productSizeId);
        $result = $productSize->update([
            'name' => $request->name,
            'user_id' => Auth::user()->id,
        ]);

        if ($result) {
            return redirect()->back()->with(['success' => 'Product Size Updated Successfully']);
        }

        return redirect()->back()->with(['error' => 'Something Went Wrong Try Again']);
    }

    public function destroy($id)
    {
        $productSize = ProductSize::find($id);

        if (!$productSize) {
            return response()->json(['message' => 'Product Size not found'], 404);
        }

        $productVariantExists = ProductVariant::where('size_id', $productSize->id)->exists();

        if ($productVariantExists) {
            return response()->json(['message' => 'This Size is assigned to one or more product variants and cannot be deleted.'], 400);
        }

        $productSize->delete();

        return response()->json(['message' => 'Size deleted successfully.'], 200);
    }
}
