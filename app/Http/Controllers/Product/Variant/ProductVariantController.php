<?php

namespace App\Http\Controllers\Product\Variant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductVariantCreateRequest;
use App\Models\Party;
use App\Models\Product\Brand\ProductBrand;
use App\Models\Product\Category\ProductCategory;
use App\Models\Product\Location\ProductLocation;
use App\Models\Product\MeasuringUnit\MeasuringUnit;
use App\Models\Product\Product;
use App\Models\Product\ProductOption\ProductSize;
use App\Models\Product\Variant\ProductVariant;
use App\Models\Product\Variant\ProductVariantLocation;
use App\Models\Product\Variant\ProductVariantRate;
use App\Models\Product\Variant\ProductVariantRateLedger;
use App\Models\Product\Variant\ProductVariantStock;
use App\Models\StockManagment\StockLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductVariantController extends Controller
{
    public function index()
    {
        $productVariants = ProductVariant::with('product', 'size', 'measuringUnit', 'rates', 'stock')
            ->whereHas('product', function ($query) {
                $query->where('is_fixed_asset', 0);
            })
            ->paginate(10);
        $all_productCategories = ProductCategory::all();
        $all_productBrands = ProductBrand::all();
        $all_suppliers = Party::where('type', 'Supplier')->get();
        $all_measuringUnits = MeasuringUnit::all();
        $all_locations = ProductLocation::all();
        $all_products = Product::all();

        return view(
            'adminPanel.product.product.productVariant.productVariantList',
            [
                'productVariants' => $productVariants,
                'all_productCategories' => $all_productCategories,
                'all_productBrands' => $all_productBrands,
                'all_suppliers' => $all_suppliers,
                'all_measuringUnits' => $all_measuringUnits,
                'all_locations' => $all_locations,
                'all_products' => $all_products
            ]
        );
    }

    public function create()
    {
        $allmeasuringUnits = MeasuringUnit::all();
        $allLocations = ProductLocation::all();
        $allSizes = ProductSize::all();

        $lastProductVariant = ProductVariant::withTrashed()->latest('id')->first();
        if ($lastProductVariant) {
            $sequence = $lastProductVariant->id + 1 + 1000;
        } else {
            $sequence = 1001;
        }
        while (ProductVariant::where('code', $sequence)->exists()) {
            $sequence++;
        }
        $newVariantCode = str_pad($sequence, 4, '0', STR_PAD_LEFT);

        return view(
            'adminPanel.product.product.productVariant.createProductVariant',
            [
                'allmeasuringUnits' => $allmeasuringUnits,
                'allLocations' => $allLocations,
                'allSizes' => $allSizes,
                'lastSizeId' => $lastProductVariant?->size_id,
                'lastMeasuringUnitId' => $lastProductVariant?->measuring_unit_id,
                'lastLocationId' => $lastProductVariant?->location_id,
                'newVariantCode' => $newVariantCode,
            ]
        );
    }

    public function getFixedAssetProducts(Request $request)
    {
        $isFixedAsset = $request->query('is_fixed_asset', 0);
        $products = Product::where('is_fixed_asset', $isFixedAsset)
            ->where('is_manage_variants', 1)
            ->with('category', 'brand', 'supplier')
            ->get();

        return response()->json(['products' => $products]);
    }

    public function store(ProductVariantCreateRequest $request)
    {
        // dd($request);
        $product = Product::find($request->product_id);
        try {
            DB::transaction(function () use ($request, $product) {

                $existingVariant = ProductVariant::where('product_id', $request->product_id)
                    ->where('size_id', $request->size_id)
                    ->where('measuring_unit_id', $request->measuring_unit_id)
                    ->first();


                if ($existingVariant) {
                    $isLocationExists = ProductVariantLocation::where('product_variant_id', $existingVariant->id)
                        ->where('location_id', $request->location_id)
                        ->exists();

                    if ($isLocationExists) {
                        throw new \Exception('This variant already exists with the specified location.');
                    }
                }

                $size = ProductSize::find($request->size_id);

                if (!$product) {
                    throw new \Exception("Product not found.");
                }
                $variantNameParts = [$product->product_name];
                $variantUrduNameParts = [$product->product_urdu_name];

                if ($request->size_id) {
                    $variantNameParts[] = strtolower($size->slug);
                    $variantUrduNameParts[] = $size->slug;
                }

                $productVariantName = implode(' ', $variantNameParts);
                $productVariantUrduName = implode(' ', $variantUrduNameParts);

                $skuParts = [];

                if ($size) {
                    $skuParts[] = strtoupper($size->slug);
                }

                $skuParts[] = $request->code;

                $sku = implode('-', $skuParts);

                if (empty($skuParts)) {
                    $sku = $request->code;
                }

                $productVariant = ProductVariant::create([
                    'product_variant_name' => $productVariantName,
                    'product_variant_urdu_name' => $productVariantUrduName,
                    'code' => $request->code,
                    'SKU' => $sku,
                    'product_id' => $request->product_id,
                    'measuring_unit_id' => $request->measuring_unit_id,
                    'size_id' => $request->size_id,
                    'description' => $request->variant_description,
                    'min_order_qty' => $request->min_order_qty,
                    'manage_deal_items' => $request->manage_deal_id,
                    'service_item' => $request->service_item,
                    'finish_goods' => $request->finish_goods,
                    'raw_material' => $request->raw_material,
                    'user_id' => Auth::user()->id,
                ]);

                if ($request->hasFile('image')) {
                    $imageName = substr($productVariantName, 0, 4) . '_' . time() . '.' . $request->file('image')->getClientOriginalExtension();
                    $productVariant
                        ->addMediaFromRequest('image')
                        ->usingFileName($imageName)
                        ->toMediaCollection('pro_var_images');
                }

                ProductVariantRate::create([
                    'product_variant_id' => $productVariant->id,
                    'cost_price' => $request->cost_price,
                    'retail_price' => $request->retail_price,
                    'wholesale_price' => $request->wholesale_price,
                    'user_id' => Auth::user()->id,
                ]);

                ProductVariantRateLedger::create([
                    'product_variant_id' => $productVariant->id,
                    'old_retail_price' => null,
                    'new_retail_price' => $request->retail_price,
                    'change_date' => now(),
                    'user_id' => Auth::user()->id,
                ]);

                if ($request->input('service_item') == 0) {


                    ProductVariantStock::create([
                        'product_variant_id' => $productVariant->id,
                        'opening_stock' => $request->opening_stock,
                        'stock' => $request->opening_stock,
                        'low_stock' => $request->low_stock,
                        'inner_pack' => $request->inner_pack,
                        'loose_pack' => $request->loose_pack,
                        'user_id' => Auth::user()->id,
                    ]);

                    ProductVariantLocation::create([
                        'product_variant_id' => $productVariant->id,
                        'location_id' => $request->location_id,
                        'stock_qty' => $request->opening_stock,
                        'user_id' => Auth::user()->id,
                    ]);

                    StockLedger::create([
                        'product_variant_id' => $productVariant->id,
                        'location_id' => $request->location_id,
                        'stock_after_transaction' => $request->opening_stock,
                        'qty' => $request->opening_stock,
                        'transfer_type' => 'in',
                        'user_id' => Auth::user()->id,
                    ]);
                }
            });
            return redirect()->route('product-variant.index')->with(['success' => $product->product_name . ' Variant Created Successfully']);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with(['error' => 'Database error: ' . $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('General error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with(['error' => 'Something went wrong. ' . $e->getMessage()]);
        }
    }

    public function getProductVariantRetailPrice($id)
    {
        $productVariant = ProductVariant::with('rates')->find($id);

        if (!$productVariant) {
            return response()->json(['error' => 'Product Variant not found.'], 404);
        }

        return response()->json([
            'product_variant_code' => $productVariant->code,
            'product_variant_name' => $productVariant->product_variant_name,
            'old_retail_price' => $productVariant->rates->retail_price,
        ]);
    }

    public function updateProductVariantRetailPrice(Request $request)
    {
        try {
            $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
            ]);

            $productVariantRate = ProductVariantRate::where('product_variant_id', $request->product_variant_id)->first();

            if (!$productVariantRate) {
                throw new \Exception('Product Variant not found.');
            }

            $productVariantRate->retail_price = $request->new_retail_price;
            $productVariantRate->save();

            return redirect()->back()->with('success', 'Retail price updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating product variant retail price: ' . $e->getMessage());
            return redirect()->back()->withInput($request->all())->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function getProductVariantCode($id)
    {
        $productVariant = ProductVariant::find($id);

        if (!$productVariant) {
            return response()->json(['error' => 'Product Variant not found.'], 404);
        }

        return response()->json([
            'product_variant_code' => $productVariant->code,
            'product_variant_name' => $productVariant->product_variant_name,
        ]);
    }

    public function updateProductVariantCode(Request $request)
    {
        try {
            $request->validate([
                'product_variant_id' => 'required|exists:product_variants,id',
            ]);

            $productVariant = ProductVariant::find($request->product_variant_id);

            if (!$productVariant) {
                throw new \Exception('Product Variant not found.');
            }

            $productVariant->code = $request->new_variant_code;
            $productVariant->save();

            return redirect()->back()->with('success', $productVariant->product_variant_name . ' Code updated Successfully');
        } catch (\Exception $e) {
            Log::error('Error updating product variant retail price: ' . $e->getMessage());
            return redirect()->back()->withInput($request->all())->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function softDestroy($id)
    {
        $productVariant = ProductVariant::find($id);

        if (!$productVariant) {
            return response()->json(['message' => 'Product Variant not found'], 404);
        }

        $productVariant->delete();

        return response()->json(['message' => 'Product variants temporarely deleted successfully.'], 200);
    }

    public function trashed()
    {
        $productVariants = ProductVariant::onlyTrashed()
            ->with('product', 'size', 'measuringUnit', 'rates', 'stock')
            ->paginate(10);

        return view(
            'adminPanel.product.product.productVariant.trashedProductVariantList',
            [
                'productVariants' => $productVariants
            ]
        );
    }

    public function restoreProductVariant($id)
    {
        $productVariant = ProductVariant::onlyTrashed()->find($id);

        if (!$productVariant) {
            return response()->json(['message' => 'Product variant not found in trash'], 404);
        }

        $product = Product::onlyTrashed()->find($productVariant->product_id);

        if ($product) {
            return response()->json(['message' => 'Associated product is in trash, restore it first.'], 400);
        }

        $productVariant->restore();

        return response()->json(['message' => 'Product variant restored successfully'], 200);
    }

    public function criteriaWiseReport(Request $request)
    {

        $productVariants = ProductVariant::query();
        $criteria = $request->input('search_criteria');
        $criteriaProductId = $request->input('criteria_product_id');
        $productCategoryId = $request->input('product_category_id');
        $productBrandId = $request->input('product_brand_id');
        $productSupplierId = $request->input('product_supplier_id');
        $productLocationId = $request->input('product_location_id');
        $productMeasuringUnitId = $request->input('product_measuring_unit_id');
        $productId = $request->input('product_id');


        $relatedData = null;
        $criteriaType = null;



        if ($criteria === 'category') {
            if ($productCategoryId !== 'all_categories') {
                if ($criteriaProductId === 'all_products') {
                    $productIds = Product::where('supplier_id', $productCategoryId)->pluck('id');
                    $productVariants->whereIn('product_id', $productIds);
                } else {
                    $productVariants->where('product_id', $criteriaProductId);
                }
            } elseif ($criteriaProductId !== 'all_products') {
                $productVariants->where('product_id', $criteriaProductId);
            } else {
            }
        }
        if ($criteria === 'brand') {
            if ($productBrandId !== 'all_brands') {
                if ($criteriaProductId === 'all_products') {
                    $productIds = Product::where('supplier_id', $productBrandId)->pluck('id');
                    $productVariants->whereIn('product_id', $productIds);
                } else {
                    $productVariants->where('product_id', $criteriaProductId);
                }
            } elseif ($criteriaProductId !== 'all_products') {
                $productVariants->where('product_id', $criteriaProductId);
            } else {
            }
        }
        if ($criteria === 'supplier') {
            if ($productSupplierId !== 'all_suppliers') {
                if ($criteriaProductId === 'all_products') {
                    $productIds = Product::where('supplier_id', $productSupplierId)->pluck('id');
                    $productVariants->whereIn('product_id', $productIds);
                } else {
                    $productVariants->where('product_id', $criteriaProductId);
                }
            } elseif ($criteriaProductId !== 'all_products') {
                $productVariants->where('product_id', $criteriaProductId);
            } else {
            }
        }

        if ($productId !== 'all_products') {
            $productVariants->where('product_id', $productId);
        }

        if ($productLocationId !== 'all_locations') {
            $productVariants->where('location_id', $productLocationId);
        }

        if ($productMeasuringUnitId !== 'all_measuringUnits') {
            $productVariants->where('measuring_unit_id', $productMeasuringUnitId);
        }

        $productVariants = $productVariants->with(['product', 'size', 'location', 'measuringUnit', 'rates', 'stock'])
            ->get();

        if ($criteria === 'category') {
            if ($productCategoryId !== 'all_categories') {
                $relatedData = ProductCategory::find($productCategoryId);
            } else {
                $relatedData = 'All';
            }
            $criteriaType = 'category';
        } elseif ($criteria === 'brand') {
            if ($productBrandId !== 'all_brands') {
                $relatedData = ProductBrand::find($productBrandId);
            } else {
                $relatedData = 'All';
            }
            $criteriaType = 'brand';
        } elseif ($criteria === 'supplier') {
            if ($productSupplierId !== 'all_suppliers') {
                $relatedData = Party::where('id', $productSupplierId)->where('type', 'supplier')->first();
            } else {
                $relatedData = 'All';
            }
            $criteriaType = 'supplier';
        } elseif ($criteria === 'product') {
            if ($productId !== 'all_products') {
                $relatedData = Product::where('id', $productId)->first();
            } else {
                $relatedData = 'All';
            }
            $criteriaType = 'product';
        } elseif ($criteria === 'locations') {
            if ($productLocationId !== 'all_locations') {
                $relatedData = ProductLocation::where('id', $productLocationId)->first();
            } else {
                $relatedData = 'All';
            }
            $criteriaType = 'locations';
        } elseif ($criteria === 'measuring_unit') {
            if ($productMeasuringUnitId !== 'all_measuringUnits') {
                $relatedData = MeasuringUnit::where('id', $productMeasuringUnitId)->first();
            } else {
                $relatedData = 'All';
            }
            $criteriaType = 'measuring_unit';
        } else {
            $relatedData = 'All';
            $criteriaType = 'all';
        }


        return view('adminPanel.product.product.productVariant.reports.criteriaWiseProductVariantReport', compact('productVariants', 'relatedData', 'criteriaType'));
    }

    public function fixedAssetProductVariants()
    {
        $productVariants = ProductVariant::with('product', 'size', 'measuringUnit', 'rates', 'stock')
            ->whereHas('product', function ($query) {
                $query->where('is_fixed_asset', 1);
            })
            ->paginate(10);

        return view(
            'adminPanel.product.product.productVariant.fixedAsset.productVariantList',
            [
                'productVariants' => $productVariants,
            ]
        );
    }
}
