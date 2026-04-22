<?php

namespace App\Http\Controllers\Product;

use App\Models\Party;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Product\Brand\ProductBrand;
use App\Models\StockManagment\StockLedger;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Category\ProductCategory;
use App\Models\Product\Location\ProductLocation;
use App\Actions\Product\GenerateProductVariantSKU;
use App\Models\Product\Variant\ProductVariantRate;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Models\Product\MeasuringUnit\MeasuringUnit;
use App\Models\Product\Variant\ProductVariantStock;
use App\Models\Product\Variant\ProductVariantLocation;
use App\Models\Product\Variant\ProductVariantRateLedger;

class ProductController extends Controller
{

    public function index()
    {
        // $products = Product::with('category', 'brand', 'supplier', 'variants')->paginate(10);

        $productVariants = ProductVariant::with('product', 'product.category', 'size', 'measuringUnit', 'rates', 'stock')
            ->whereHas('product', function ($query) {
                $query->where('is_fixed_asset', 0);
            })
            ->get();

        $all_productCategories = ProductCategory::all();
        $all_productBrands = ProductBrand::all();
        $all_suppliers = Party::where('type', 'Supplier')->get();

        return view(
            'adminPanel.product.product.productList',
            [
                // 'products' => $products,
                'productVariants' => $productVariants,
                'all_productCategories' => $all_productCategories,
                'all_productBrands' => $all_productBrands,
                'all_suppliers' => $all_suppliers
            ]
        );
    }

    public function create()
    {
        $allproductCategories = ProductCategory::all();
        $allproductBrands = ProductBrand::all();
        $allSuppliers = Party::where('type', 'Supplier')->get();
        $allmeasuringUnits = MeasuringUnit::all();
        $allLocations = ProductLocation::all();

        $lastProduct = Product::withTrashed()->latest('id')->first();
        $lastProductVariant = ProductVariant::withTrashed()->latest('id')->first();

        if ($lastProductVariant) {
            $lastProductVariantLocation = ProductVariantLocation::withTrashed()
                ->where('product_variant_id', $lastProductVariant->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $lastLocationId = $lastProductVariantLocation ? $lastProductVariantLocation->location_id : null;
        } else {
            $lastLocationId = null;
        }

        if ($lastProduct) {
            $sequence = $lastProduct->id + 1 + 1000;
        } else {
            $sequence = 1001;
        }
        while (Product::where('code', $sequence)->exists() || ProductVariant::where('code', $sequence)->exists()) {
            $sequence++;
        }

        $productCode = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return view(
            'adminPanel.product.product.createProduct',
            [
                'allproductCategories' => $allproductCategories,
                'allproductBrands' => $allproductBrands,
                'allSuppliers' => $allSuppliers,
                'allmeasuringUnits' => $allmeasuringUnits,
                'allLocations' => $allLocations,
                'lastCategoryId' => $lastProduct?->category_id,
                'lastBrandId' => $lastProduct?->brand_id,
                'lastSupplierId' => $lastProduct?->supplier_id,
                'lastMeasuringUnitId' => $lastProductVariant?->measuring_unit_id,
                'lastLocationId' => $lastLocationId,
                'productCode' => $productCode,
            ]
        );
    }

    public function getProductSuggestions(Request $request)
    {
        $query = $request->name;
        if ($query) {
            $products = Product::where('product_name', 'LIKE', "$query%")
                ->take(10)
                ->get(['product_name']);
        } else {
            $products = collect();
        }

        return response()->json($products);
    }

    // Store method is commented out to avoid conflicts with the new store method Dont delete it
    // public function store(ProductCreateRequest $request)
    // {
    //     try {
    //         DB::transaction(function () use ($request) {
    //             $category = ProductCategory::find($request->category_id);
    //             $brand = ProductBrand::find($request->brand_id);
    //             $skuGenerator = new GenerateProductVariantSKU();
    //             $sku = $skuGenerator->execute($category, $request->size, $request->color, $request->code);

    //             $product = Product::create([
    //                 'product_name' => $request->product_name,
    //                 'product_urdu_name' => $request->product_urdu_name,
    //                 'code' => $request->code,
    //                 'description' => $request->product_description,
    //                 'is_manage_variants' => $request->is_manage_variants,
    //                 'is_fixed_asset' => $request->is_fixed_asset,
    //                 'is_featured' => $request->is_featured,
    //                 'new_arrival' => $request->new_arrival,
    //                 'best_selling_product' => $request->best_selling_product,
    //                 'category_id' => $request->category_id,
    //                 'brand_id' => $request->brand_id ?? null,
    //                 'supplier_id' => $request->supplier_id ?? null,
    //                 'user_id' => Auth::user()->id,
    //             ]);

    //             if ($request->hasFile('product_image')) {
    //                 $imageName = substr($request->product_name, 0, 4) . '_' . $request->code . '_' . time() . '.' . $request->file('product_image')->getClientOriginalExtension();
    //                 $product
    //                     ->addMediaFromRequest('product_image')
    //                     ->usingFileName($imageName)
    //                     ->toMediaCollection('pro_thumbnail_images');
    //             }

    //             if ($request->input('is_manage_variants') == 0) {
    //                 $productVariant = ProductVariant::create([
    //                     'product_variant_name' => $product->product_name,
    //                     'product_variant_urdu_name' => $product->product_urdu_name,
    //                     'code' => $request->code,
    //                     'SKU' => $sku,
    //                     'product_id' => $product->id,
    //                     'measuring_unit_id' => $request->measuring_unit_id,
    //                     'description' => $request->product_description,
    //                     'min_order_qty' => $request->min_order_qty,
    //                     'manage_deal_items' => $request->manage_deal_id ?? 0,
    //                     'service_item' => $request->service_item ?? 0,
    //                     'finish_goods' => $request->finish_goods ?? 0,
    //                     'raw_material' => $request->raw_material ?? 0,
    //                     'user_id' => Auth::user()->id,
    //                 ]);

    //                 if ($request->hasFile('image')) {
    //                     $imageName = substr($request->product_name, 0, 4) . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
    //                     $productVariant
    //                         ->addMediaFromRequest('image')
    //                         ->usingFileName($imageName)
    //                         ->toMediaCollection('pro_var_images');
    //                 }

    //                 ProductVariantRate::create([
    //                     'product_variant_id' => $productVariant->id,
    //                     'cost_price' => $request->cost_price,
    //                     'retail_price' => $request->retail_price,
    //                     'user_id' => Auth::user()->id,
    //                 ]);

    //                 ProductVariantRateLedger::create([
    //                     'product_variant_id' => $productVariant->id,
    //                     'old_retail_price' => null,
    //                     'new_retail_price' => $request->retail_price,
    //                     'change_date' => now(),
    //                     'user_id' => Auth::user()->id,
    //                 ]);

    //                 // Only skip stock creation if it's a service item
    //                 if (($request->service_item ?? 0) != 1) {
    //                     ProductVariantStock::create([
    //                         'product_variant_id' => $productVariant->id,
    //                         'opening_stock' => $request->opening_stock ?? 0,
    //                         'stock' => $request->opening_stock ?? 0,
    //                         'low_stock' => $request->low_stock ?? 0,
    //                         'inner_pack' => $request->inner_pack ?? 1,
    //                         'loose_pack' => $request->loose_pack ?? 1,
    //                         'user_id' => Auth::user()->id,
    //                     ]);

    //                     StockLedger::create([
    //                         'product_variant_id' => $productVariant->id,
    //                         'location_id' => $request->product_location_id,
    //                         'stock_after_transaction' => $request->opening_stock ?? 0,
    //                         'qty' => $request->opening_stock ?? 0,
    //                         'transfer_type' => 'in',
    //                         'user_id' => Auth::user()->id,
    //                     ]);

    //                     ProductVariantLocation::create([
    //                         'product_variant_id' => $productVariant->id,
    //                         'location_id' => $request->product_location_id,
    //                         'stock_qty' => $request->opening_stock ?? 0,
    //                         'user_id' => Auth::user()->id,
    //                     ]);
    //                 }
    //             }
    //         });
    //         return redirect()->route('product.index')->with(['success' => 'Product Created Successfully']);
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         Log::error('Database error: ' . $e->getMessage());
    //         return redirect()->back()->withInput()->with(['error' => 'Database error: ' . $e->getMessage()]);
    //     } catch (\Exception $e) {
    //         Log::error('General error: ' . $e->getMessage());
    //         return redirect()->back()->withInput()->with(['error' => 'Something went wrong. ' . $e->getMessage()]);
    //     }
    // }

    // Store the Product using the ajax request
    public function store(ProductCreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $category = ProductCategory::find($request->category_id);
                $brand = ProductBrand::find($request->brand_id);
                $skuGenerator = new GenerateProductVariantSKU();
                $sku = $skuGenerator->execute($category, $request->size, $request->color, $request->code);

                $product = Product::create([
                    'product_name' => $request->product_name,
                    'product_urdu_name' => $request->product_urdu_name,
                    'code' => $request->code,
                    'description' => $request->product_description,
                    'is_manage_variants' => $request->is_manage_variants,
                    'is_fixed_asset' => $request->is_fixed_asset,
                    'is_featured' => $request->is_featured,
                    'new_arrival' => $request->new_arrival,
                    'best_selling_product' => $request->best_selling_product,
                    'category_id' => $request->category_id,
                    'brand_id' => $request->brand_id ?? null,
                    'supplier_id' => $request->supplier_id ?? null,
                    'user_id' => Auth::user()->id,
                ]);

                if ($request->hasFile('product_image')) {
                    $imageName = substr($request->product_name, 0, 4) . '_' . $request->code . '_' . time() . '.' . $request->file('product_image')->getClientOriginalExtension();
                    $product
                        ->addMediaFromRequest('product_image')
                        ->usingFileName($imageName)
                        ->toMediaCollection('pro_thumbnail_images');
                }

                if ($request->input('is_manage_variants') == 0) {
                    $productVariant = ProductVariant::create([
                        'product_variant_name' => $product->product_name,
                        'product_variant_urdu_name' => $product->product_urdu_name,
                        'code' => $request->code,
                        'SKU' => $sku,
                        'product_id' => $product->id,
                        'measuring_unit_id' => $request->measuring_unit_id,
                        'description' => $request->product_description,
                        'min_order_qty' => $request->min_order_qty,
                        'manage_deal_items' => $request->manage_deal_id ?? 0,
                        'service_item' => $request->service_item ?? 0,
                        'finish_goods' => $request->finish_goods ?? 0,
                        'raw_material' => $request->raw_material ?? 0,
                        'user_id' => Auth::user()->id,
                    ]);

                    if ($request->hasFile('image')) {
                        $imageName = substr($request->product_name, 0, 4) . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                        $productVariant
                            ->addMediaFromRequest('image')
                            ->usingFileName($imageName)
                            ->toMediaCollection('pro_var_images');
                    }

                    ProductVariantRate::create([
                        'product_variant_id' => $productVariant->id,
                        'cost_price' => $request->cost_price,
                        'retail_price' => $request->retail_price,
                        'user_id' => Auth::user()->id,
                    ]);

                    ProductVariantRateLedger::create([
                        'product_variant_id' => $productVariant->id,
                        'old_retail_price' => null,
                        'new_retail_price' => $request->retail_price,
                        'change_date' => now(),
                        'user_id' => Auth::user()->id,
                    ]);

                    if (($request->service_item ?? 0) != 1) {
                        ProductVariantStock::create([
                            'product_variant_id' => $productVariant->id,
                            'opening_stock' => $request->opening_stock ?? 0,
                            'stock' => $request->opening_stock ?? 0,
                            'low_stock' => $request->low_stock ?? 0,
                            'inner_pack' => $request->inner_pack ?? 1,
                            'loose_pack' => $request->loose_pack ?? 1,
                            'user_id' => Auth::user()->id,
                        ]);

                        StockLedger::create([
                            'product_variant_id' => $productVariant->id,
                            'location_id' => $request->product_location_id,
                            'stock_after_transaction' => $request->opening_stock ?? 0,
                            'qty' => $request->opening_stock ?? 0,
                            'transfer_type' => 'in',
                            'user_id' => Auth::user()->id,
                        ]);

                        ProductVariantLocation::create([
                            'product_variant_id' => $productVariant->id,
                            'location_id' => $request->product_location_id,
                            'stock_qty' => $request->opening_stock ?? 0,
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Product Created Successfully'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            Log::error('General error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $productVariantId = $id;
        $productVariant = ProductVariant::with('product', 'product.category', 'measuringUnit', 'productVariantLocation', 'rates', 'stock')
            ->where('id', $productVariantId)
            ->first();

        // dd($productVariant);

        $allproductCategories = ProductCategory::all();
        $allproductBrands = ProductBrand::all();
        $allSuppliers = Party::where('type', 'Supplier')->get();
        $allmeasuringUnits = MeasuringUnit::all();
        $allLocations = ProductLocation::all();

        return view(
            'adminPanel.product.product.editProduct',
            [
                'allproductCategories' => $allproductCategories,
                'allproductBrands' => $allproductBrands,
                'allSuppliers' => $allSuppliers,
                'allmeasuringUnits' => $allmeasuringUnits,
                'allLocations' => $allLocations,
                'productVariant' => $productVariant,
                'productVariantId' => $productVariantId,
            ]
        );
    }

    public function update(ProductUpdateRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $productVariant = ProductVariant::findOrFail($request->product_variant_id);
                $product = $productVariant->product;

                $product->update([
                    'product_name' => $request->product_name,
                    'product_urdu_name' => $request->product_urdu_name,
                    'code' => $request->code,
                    'category_id' => $request->category_id,
                    'is_manage_variants' => $request->is_manage_variants ?? 0,
                    'is_fixed_asset' => $request->is_fixed_asset ?? 0,
                    'is_featured' => $request->is_featured ?? 0,
                    'new_arrival' => $request->new_arrival ?? 0,
                    'best_selling_product' => $request->best_selling_product ?? 0,
                    'user_id' => Auth::user()->id,
                ]);

                $productVariant->update([
                    'product_variant_name' => $request->product_name,
                    'product_variant_urdu_name' => $request->product_urdu_name,
                    'code' => $request->code,
                    'measuring_unit_id' => $request->measuring_unit_id,
                    'min_order_qty' => $request->min_order_qty,
                    'description' => $request->product_description,
                    'service_item' => $request->service_item ?? 0,
                    'finish_goods' => $request->finish_goods ?? 0,
                    'raw_material' => $request->raw_material ?? 0,
                    'manage_deal_items' => $request->manage_deal_id ?? 0,
                    'user_id' => Auth::user()->id,
                ]);

                $oldRetailPrice = $productVariant->rates->retail_price;
                $productVariant->rates()->update([
                    'retail_price' => $request->retail_price,
                    'user_id' => Auth::user()->id,
                ]);

                ProductVariantRateLedger::create([
                    'product_variant_id' => $productVariant->id,
                    'old_retail_price' => $oldRetailPrice,
                    'new_retail_price' => $request->retail_price,
                    'change_date' => now(),
                    'user_id' => Auth::user()->id,
                ]);

                if (($request->service_item ?? 0) != 1) {
                    $productVariant->stock()->update([
                        'low_stock' => $request->low_stock ?? 0,
                        'user_id' => Auth::user()->id,
                    ]);
                }

                if ($request->hasFile('image')) {
                    $productVariant->clearMediaCollection('pro_var_images');
                    $imageName = substr($request->product_name, 0, 4) . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    $productVariant->addMediaFromRequest('image')
                        ->usingFileName($imageName)
                        ->toMediaCollection('pro_var_images');
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $validated = $request->validate([
            'productId' => 'required|integer|exists:products,id',
            'field' => 'required|string|in:is_featured,new_arrival,best_selling_product',
            'status' => 'required|boolean',
        ]);

        try {
            $product = Product::findOrFail($request->productId);

            // Update the relevant field with the new status
            $product->{$request->field} = $request->status;
            $product->save();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Error updating product status: ' . $e->getMessage());
            return response()->json(['success' => false]);
        }
    }

    public function softDestroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Product and its related variants temporarely deleted successfully.'], 200);
    }

    public function trashed()
    {
        $productVariants = ProductVariant::onlyTrashed()
            ->with('product', 'size', 'measuringUnit', 'rates', 'stock')
            ->paginate(10);

        return view(
            'adminPanel.product.product.trashedProductList',
            [
                'productVariants' => $productVariants
            ]
        );
    }

    public function restoreProduct($id)
    {
        $product = Product::onlyTrashed()->find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found in trash'], 404);
        }

        $product->restore();

        return response()->json(['message' => 'Product and its related variants restored successfully'], 200);
    }

    public function lowStock()
    {

        $lowStockVariantIds = ProductVariantStock::whereColumn('stock', '<=', 'low_stock')
            ->pluck('product_variant_id');

        $productVariants = ProductVariant::with('product', 'size', 'productVariantLocation.ProductLocation', 'measuringUnit', 'rates', 'stock')
            ->whereIn('id', $lowStockVariantIds)
            ->paginate(10);


        return view(
            'adminPanel.product.product.productVariant.lowStockList',
            [
                'productVariants' => $productVariants
            ]
        );
    }

    public function criteriaWiseReport(Request $request)
    {
        $criteria = $request->input('search_criteria');
        $products = Product::query();

        $relatedData = null;
        $criteriaType = null;

        switch ($criteria) {
            case 'category':
                if ($request->input('product_category_id') !== 'all_categories') {
                    $products->where('category_id', $request->input('product_category_id'));
                }
                break;

            case 'brand':
                if ($request->input('product_brand_id') !== 'all_brands') {
                    $products->where('brand_id', $request->input('product_brand_id'));
                }
                break;

            case 'supplier':
                if ($request->input('product_supplier_id') !== 'all_suppliers') {
                    $products->where('supplier_id', $request->input('product_supplier_id'));
                }
                break;

            default:
                break;
        }

        $products = $products->with(['supplier', 'brand', 'category'])->withCount('variants')->get();

        switch ($criteria) {
            case 'category':
                if ($request->input('product_category_id') !== 'all_categories') {
                    $relatedData = ProductCategory::find($request->input('product_category_id'));
                } else {
                    $relatedData = 'All';
                }
                $criteriaType = 'category';
                break;

            case 'brand':
                if ($request->input('product_brand_id') !== 'all_brands') {
                    $relatedData = ProductBrand::find($request->input('product_brand_id'));
                } else {
                    $relatedData = 'All';
                }
                $criteriaType = 'brand';
                break;

            case 'supplier':
                if ($request->input('product_supplier_id') !== 'all_suppliers') {
                    $relatedData = Party::where('id', $request->input('product_supplier_id'))->where('type', 'supplier')->first();
                } else {
                    $relatedData = 'All';
                }
                $criteriaType = 'supplier';
                break;
            default:
                $relatedData = 'All';
                $criteriaType = 'all';
                break;
        }



        return view('adminPanel.product.product.reports.criteriaWiseProductReport', compact('products', 'relatedData', 'criteriaType'));
    }

    public function getProductsByCategory($category_id)
    {
        if ($category_id == 'all_categories') {
            $products = Product::all();
        } else {
            $products = Product::where('category_id', $category_id)->get();
        }

        return response()->json(['products' => $products]);
    }

    public function getProductsByBrand($brand_id)
    {
        if ($brand_id == 'all_brands') {
            $products = Product::all();
        } else {
            $products = Product::where('brand_id', $brand_id)->get();
        }

        return response()->json(['products' => $products]);
    }

    public function getProductsBySupplier($supplier_id)
    {
        if ($supplier_id == 'all_suppliers') {
            $products = Product::all();
        } else {
            $products = Product::where('supplier_id', $supplier_id)->get();
        }

        return response()->json(['products' => $products]);
    }

    public function fixedAssetProducts()
    {
        $products = Product::with('category', 'brand', 'supplier')
            ->where('is_fixed_asset', 1)
            ->paginate(10);

        return view(
            'adminPanel.product.product.fixedAsset.productList',
            [
                'products' => $products,
            ]
        );
    }

    // Receipe


}
