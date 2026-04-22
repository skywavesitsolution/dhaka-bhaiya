<?php

namespace App\Http\Controllers\Product\StockAdjustment;

use App\Http\Controllers\Controller;
use App\Models\Party;
use App\Models\Product\Brand\ProductBrand;
use App\Models\Product\Category\ProductCategory;
use App\Models\Product\Location\ProductLocation;
use App\Models\Product\MeasuringUnit\MeasuringUnit;
use App\Models\Product\Product;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Variant\ProductVariantLocation;
use App\Models\Product\Variant\ProductVariantStock;
use App\Models\StockManagment\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $all_productCategories = ProductCategory::all();
        $all_productBrands = ProductBrand::all();
        $all_suppliers = Party::where('type', 'Supplier')->get();
        $all_measuringUnits = MeasuringUnit::all();
        $all_locations = ProductLocation::all();
        $all_products = Product::all();

        return view(
            'adminPanel.product.product.productVariant.variantStockAdjustment.productVariantStockAdjustment',
            [
                'all_productCategories' => $all_productCategories,
                'all_productBrands' => $all_productBrands,
                'all_suppliers' => $all_suppliers,
                'all_measuringUnits' => $all_measuringUnits,
                'all_locations' => $all_locations,
                'all_products' => $all_products
            ]
        );
    }

    public function getVariantStock(Request $request)
    {
        $criteria = $request->criteria;
        $id = $request->id;

        $data = [];

        if ($criteria == 'product') {
            $data = ProductVariant::where('product_id', $id)->with('stock')->get();
        } elseif ($criteria == 'category' || $criteria == 'categories') {
            $data = ProductVariant::whereHas('product', function ($query) use ($id) {
                $query->where('category_id', $id);
            })->with('stock')->get();
        } elseif ($criteria == 'brand' || $criteria == 'brands') {
            $data = ProductVariant::whereHas('product', function ($query) use ($id) {
                $query->where('brand_id', $id);
            })->with('stock')->get();
        } elseif ($criteria == 'supplier' || $criteria == 'suppliers') {
            $data = ProductVariant::whereHas('product', function ($query) use ($id) {
                $query->where('supplier_id', $id);
            })->with('stock')->get();
        } elseif ($criteria == 'locations') {
            $data = ProductVariantLocation::where('location_id', $id)
                ->with('productVariant.stock')
                ->get();
        } elseif ($criteria == 'measuring_unit') {
            $data = ProductVariant::where('measuring_unit_id', $id)->with('stock')->get();
        } else {
            return response()->json(['error' => 'Invalid criteria provided.'], 400);
        }

        // dd($data);

        return response()->json(['data' => $data]);
    }

    // public function updateVariantStock(Request $request)
    // {
    //     $request->validate([
    //         'form_data' => 'required|array',
    //         'form_data.*.variant_location_id' => 'required|exists:product_variant_locations,id',
    //         'form_data.*.location_id' => 'required|exists:product_locations,id',
    //         'form_data.*.variant_id' => 'required|exists:product_variants,id',
    //         'form_data.*.entered_stock' => 'required|numeric|min:0',
    //     ]);

    //     try {
    //         DB::transaction(function () use ($request) {

    //             foreach ($request->form_data as $item) {
    //                 $productVariantLocation = ProductVariantLocation::find($item['variant_location_id']);
    //                 if ($productVariantLocation) {
    //                     $oldStockQty = $productVariantLocation->stock_qty;
    //                     $newStockQty = $item['entered_stock'];
    //                     $stockDifference = $newStockQty - $oldStockQty;
    //                     $productVariantLocation->stock_qty = $newStockQty;
    //                     $productVariantLocation->save();

    //                     $variantStock = ProductVariantStock::where('product_variant_id', $item['variant_id'])->first();
    //                     if ($variantStock) {
    //                         $variantStock->stock = $newStockQty;
    //                         $variantStock->save();
    //                     } else {
    //                         Log::warning("Variant stock not found for variant_id: " . $item['variant_id']);
    //                     }
    //                     if ($stockDifference != 0) {
    //                         StockLedger::create([
    //                             'product_variant_id' => $item['variant_id'],
    //                             'location_id' => $item['location_id'],
    //                             'qty' => $newStockQty,
    //                             'stock_after_transaction' => $newStockQty,
    //                             'transfer_type' => 'adjustment',
    //                             'user_id' => Auth::user()->id,
    //                         ]);
    //                     }
    //                 } else {
    //                     Log::warning("Product variant location not found for variant_location_id: " . $item['variant_location_id']);
    //                 }
    //             }
    //         });
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Product variants stock adjusted successfully!'
    //         ], 200);
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         Log::error('Database error: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Database error: ' . $e->getMessage()
    //         ], 500);
    //     } catch (\Exception $e) {
    //         Log::error('General error: ' . $e->getMessage());
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'An error occurred: ' . $e->getMessage(),
    //         ], 500);
    //     }
    // }

    public function updateVariantStock(Request $request)
    {
        $request->validate([
            'form_data' => 'required|array',
            'form_data.*.variant_id' => 'required|exists:product_variants,id',
            'form_data.*.entered_stock' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->form_data as $item) {
                    $variantStock = ProductVariantStock::updateOrCreate(
                        ['product_variant_id' => $item['variant_id']],
                        [
                            'stock' => $item['entered_stock'],
                            'user_id' => Auth::user()->id,
                        ]
                    );

                    $oldStockQty = $variantStock->getOriginal('stock') ?? 0;
                    $newStockQty = $item['entered_stock'];
                    $stockDifference = $newStockQty - $oldStockQty;

                    if ($stockDifference != 0) {
                        $anyLocation = ProductVariantLocation::where('product_variant_id', $item['variant_id'])->first();

                        StockLedger::create([
                            'product_variant_id' => $item['variant_id'],
                            'location_id' => $anyLocation ? $anyLocation->location_id : null,
                            'qty' => $stockDifference,
                            'stock_after_transaction' => $newStockQty,
                            'transfer_type' => 'adjustment',
                            'notes' => 'Stock adjustment via category criteria',
                            'user_id' => Auth::user()->id,
                        ]);
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Product variants stock adjusted successfully!'
            ], 200);
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
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
