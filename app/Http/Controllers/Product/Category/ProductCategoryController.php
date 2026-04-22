<?php

namespace App\Http\Controllers\Product\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\Category\ProductCategory;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $productCategories = ProductCategory::with('user')->paginate(10);
        $allproductCategories = ProductCategory::all();
        return view(
            'adminPanel.product.category.productCategoryList',
            [
                'productCategories' => $productCategories,
                'allproductCategories' => $allproductCategories,
            ]
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $productCategory = ProductCategory::create([
                    'name' => $request->name,
                    'user_id' => Auth::user()->id,
                ]);

                if ($request->hasFile('image')) {
                    $imageName = substr($request->name, 0, 4) . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    $productCategory
                        ->addMediaFromRequest('image')
                        ->usingFileName($imageName)
                        ->toMediaCollection('category_images');
                }
            });

            return redirect()->back()->with(['success' => 'Product Category added successfully']);
        } catch (\Exception $e) {
            Log::error('Error storing product category: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Something went wrong. Check logs for details.']);
        }
    }

    public function show(string $id)
    {
        $productCategory = ProductCategory::find($id);

        if ($productCategory) {
            $imageUrl = $productCategory->getFirstMediaUrl('category_images');
            return response()->json([
                'data' => $productCategory,
                'image_url' => $imageUrl
            ]);
        }

        return response()->json(['error' => 'Product Category not found'], 404);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        try {
            DB::transaction(function () use ($request) {
                $productCategory = ProductCategory::findOrFail($request->productCategoryId);
                $productCategory->update([
                    'name' => $request->name,
                    'user_id' => Auth::user()->id,
                ]);
                if ($request->hasFile('image')) {
                    $currentMedia = $productCategory->getFirstMedia('category_images');
                    if ($currentMedia) {
                        $currentMedia->delete();
                    }
                    $imageName = substr($request->name, 0, 4) . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    $productCategory
                        ->addMediaFromRequest('image')
                        ->usingFileName($imageName)
                        ->toMediaCollection('category_images');
                }
            });
            return redirect()->back()->with(['success' => 'Product Category Updated Successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating product category: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Something went wrong. Check logs for details.']);
        }
    }

    public function changeProductCategory(Request $request)
    {
        $request->validate([
            'from_category_id' => 'required|exists:product_categories,id',
            'to_category_id' => 'required|exists:product_categories,id',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $products = Product::where('category_id', $request->from_category_id)->get();
                if ($products->isNotEmpty()) {
                    foreach ($products as $product) {
                        $product->update([
                            'category_id' => $request->to_category_id,
                        ]);
                    }
                }
            });
            return redirect()->back()->with(['success' => 'Product Category Updated Successfully']);
        } catch (\Exception $e) {
            Log::error('Error updating product category: ' . $e->getMessage());
            return redirect()->back()->with(['error' => 'Something went wrong. Check logs for details.']);
        }
    }

    public function destroy($id)
    {
        $productCategory = ProductCategory::find($id);

        if (!$productCategory) {
            return response()->json(['message' => 'Product Category not found'], 404);
        }

        $productExists = Product::where('category_id', $productCategory->id)->exists();

        if ($productExists) {
            return response()->json(['message' => 'This category is assigned to one or more products and cannot be deleted.'], 400);
        }

        $productCategory->delete();

        return response()->json(['message' => 'Category deleted successfully.'], 200);
    }
}
